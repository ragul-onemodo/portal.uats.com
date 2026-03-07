<?php

namespace App\Filesystem;

use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToRetrieveMetadata;

class CustomAdapter implements FilesystemAdapter
{
    protected array $config;
    protected CustomStorageClient $client; // Your storage client

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initializeClient();
    }

    protected function initializeClient()
    {
        // Initialize your storage client here
        // Example: AWS S3, Google Cloud, custom API, etc.
        $this->client = new CustomStorageClient($this->config);
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->client->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        try {
            return $this->client->directoryExists($path);
        } catch (\Exception $e) {
            return false;
        }
    }


    public function createDirectory(string $path, Config $config): void
    {
        try {
            $success = $this->client->createDirectory($path);
            if (!$success) {
                throw UnableToCreateDirectory::atLocation($path, 'Failed to create directory');
            }
        } catch (\Exception $e) {
            throw UnableToCreateDirectory::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $items = $this->client->list($path, $deep);

        foreach ($items as $item) {
            if (isset($item['type']) && $item['type'] === 'dir') {
                yield new DirectoryAttributes($item['path']);
            } else {
                yield new FileAttributes(
                    $item['path'],
                    $item['size'] ?? null,
                    $item['visibility'] ?? null,
                    $item['last_modified'] ?? null,
                    $item['mime_type'] ?? null
                );
            }
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            $this->client->put($path, $contents, [
                'mimetype' => $config->get('mimetype'),
                'visibility' => $config->get('visibility', 'private'),
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->write($path, stream_get_contents($contents), $config);
    }

    public function read(string $path): string
    {
        try {
            return $this->client->get($path);
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function readStream(string $path)
    {
        $content = $this->read($path);
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        return $stream;
    }

    public function delete(string $path): void
    {
        try {
            $this->client->delete($path);
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        // Implement directory deletion
    }


    public function setVisibility(string $path, string $visibility): void
    {
        $this->client->setVisibility($path, $visibility);
    }

    public function visibility(string $path): FileAttributes
    {
        $visibility = $this->client->getVisibility($path);

        return new FileAttributes(
            $path,
            null,
            $visibility,
            null,
            null
        );
    }

    public function mimeType(string $path): FileAttributes
    {
        $mimeType = $this->client->getMimeType($path);

        return new FileAttributes(
            $path,
            null,
            null,
            null,
            $mimeType
        );
    }

    public function lastModified(string $path): FileAttributes
    {
        $timestamp = $this->client->getLastModified($path);

        return new FileAttributes(
            $path,
            null,
            null,
            $timestamp,
            null
        );
    }

    public function fileSize(string $path): FileAttributes
    {
        $size = $this->client->getSize($path);

        return new FileAttributes(
            $path,
            $size,
            null,
            null,
            null
        );
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $content = $this->read($source);
        $this->write($destination, $content, $config);
        $this->delete($source);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $content = $this->read($source);
        $this->write($destination, $content, $config);
    }
}
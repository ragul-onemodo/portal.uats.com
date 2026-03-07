<?php

namespace App\Filesystem;

class CustomStorageClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected array $headers;

    public function __construct(array $config)
    {
        $this->baseUrl = $config['base_url'] ?? '';
        $this->apiKey = $config['api_key'] ?? '';
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    public function exists(string $path): bool
    {
        $response = $this->makeRequest('HEAD', $path);
        return $response->getStatusCode() === 200;
    }

    public function get(string $path): string
    {
        $response = $this->makeRequest('GET', $path);
        return $response->getBody()->getContents();
    }

    public function put(string $path, string $contents, array $options = []): bool
    {
        $response = $this->makeRequest('PUT', $path, [
            'body' => $contents,
            'headers' => array_merge($this->headers, [
                'Content-Type' => $options['mimetype'] ?? 'application/octet-stream',
            ]),
        ]);

        return $response->getStatusCode() === 200;
    }

    public function delete(string $path): bool
    {
        $response = $this->makeRequest('DELETE', $path);
        return $response->getStatusCode() === 200;
    }

    protected function makeRequest(string $method, string $path, array $options = [])
    {
        $client = new \GuzzleHttp\Client();

        return $client->request($method, $this->baseUrl . '/' . ltrim($path, '/'), array_merge([
            'headers' => $this->headers,
        ], $options));
    }


    public function directoryExists(string $path): bool
    {
        // Check if path ends with a slash, add if not for directory checking
        $directoryPath = rtrim($path, '/') . '/';

        try {
            // Method 1: Try to list directory contents
            $response = $this->makeRequest('GET', $directoryPath . '?list=true');

            // If we get a 200 OK, directory exists
            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody()->getContents(), true);

                // Check if response indicates it's a directory
                // This depends on your API response format
                return isset($body['is_directory']) || isset($body['contents']);
            }

            return false;

        } catch (\Exception $e) {
            // Method 2: Try to create a test file and delete it
            try {
                $testPath = $directoryPath . '.directory_test_' . uniqid();
                $testResponse = $this->makeRequest('PUT', $testPath, [
                    'body' => 'test',
                    'headers' => array_merge($this->headers, [
                        'Content-Type' => 'text/plain',
                    ]),
                ]);

                if ($testResponse->getStatusCode() === 200) {
                    // Delete the test file
                    $this->makeRequest('DELETE', $testPath);
                    return true;
                }
            } catch (\Exception $e) {
                // Method 3: Try HEAD request on directory path
                try {
                    $headResponse = $this->makeRequest('HEAD', $directoryPath);
                    return $headResponse->getStatusCode() === 200;
                } catch (\Exception $e) {
                    return false;
                }
            }
        }

        return false;
    }


    public function createDirectory(string $path): bool
    {
        // Ensure path ends with slash for directory
        $directoryPath = rtrim($path, '/') . '/';

        // Some APIs accept PUT with empty body to create directory
        // Others might have a specific endpoint
        try {
            $response = $this->makeRequest('PUT', $directoryPath, [
                'body' => '',
                'headers' => array_merge($this->headers, [
                    'Content-Type' => 'application/x-directory',
                ]),
            ]);

            return $response->getStatusCode() === 200 || $response->getStatusCode() === 201;
        } catch (\Exception $e) {
            // Alternative: Create a marker file to indicate directory
            $markerPath = $directoryPath . '.dir_marker';
            return $this->put($markerPath, '');
        }
    }

    public function list(string $path, bool $deep = false): array
    {
        $queryParams = [];
        if ($deep) {
            $queryParams['recursive'] = 'true';
        }

        $queryString = http_build_query($queryParams);
        $url = $path . ($queryString ? '?' . $queryString : '');

        $response = $this->makeRequest('GET', $url);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['contents'] ?? $data['items'] ?? [];
        }

        return [];
    }


    public function getVisibility(string $path): string
    {
        try {
            $response = $this->makeRequest('GET', $path . '?metadata=visibility');
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['visibility'] ?? 'private';
        } catch (\Exception $e) {
            return 'private';
        }
    }

    public function setVisibility(string $path, string $visibility): bool
    {
        $response = $this->makeRequest('PATCH', $path, [
            'body' => json_encode(['visibility' => $visibility]),
            'headers' => array_merge($this->headers, [
                'Content-Type' => 'application/json',
            ]),
        ]);

        return $response->getStatusCode() === 200;
    }

    public function getMimeType(string $path): string
    {
        try {
            $response = $this->makeRequest('HEAD', $path);
            $contentType = $response->getHeaderLine('Content-Type');
            return $contentType ?: 'application/octet-stream';
        } catch (\Exception $e) {
            return 'application/octet-stream';
        }
    }

    public function getLastModified(string $path): int
    {
        try {
            $response = $this->makeRequest('HEAD', $path);
            $lastModified = $response->getHeaderLine('Last-Modified');

            if ($lastModified) {
                return strtotime($lastModified);
            }

            return time();
        } catch (\Exception $e) {
            return time();
        }
    }

    public function getSize(string $path): int
    {
        try {
            $response = $this->makeRequest('HEAD', $path);
            $contentLength = $response->getHeaderLine('Content-Length');
            return $contentLength ? (int) $contentLength : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
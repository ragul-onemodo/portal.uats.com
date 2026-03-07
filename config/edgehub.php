<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Edge Hub Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for the Edge Hub module of the
    | application. These settings include parameters for device communication,
    | data handling, and other edge-specific configurations.
    |
    */

    'image_upload_size_mb' => env('EDGE_HUB_IMAGE_UPLOAD_SIZE_MB', 5),


    'ocr' => [
        'endpoint' => env('OCR_ENDPOINT', 'https://onemodo.com/'),
        'min_confidence' => env('OCR_MIN_CONFIDENCE', 80),
    ],

];
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function uploadToCloudinary($filePath)
{
    $config = require __DIR__ . '/../config/cloudinary.php';
    error_log("Cloudinary Config: " . print_r($config, true)); // Log configuration

    Configuration::instance([
        'cloud' => [
            'cloud_name' => $config['cloud_name'],
            'api_key' => $config['api_key'],
            'api_secret' => $config['api_secret'],
        ],
        'url' => [
            'secure' => true
        ]
    ]);

    $result = (new UploadApi())->upload($filePath);
    return $result['secure_url'];
}
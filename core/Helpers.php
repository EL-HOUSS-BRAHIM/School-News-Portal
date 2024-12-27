<?php

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function uploadToCloudinary($filePath)
{
    $config = require __DIR__ . '/../config/cloudinary.php';
    \Cloudinary::config([
        'cloud_name' => $config['cloud_name'],
        'api_key' => $config['api_key'],
        'api_secret' => $config['api_secret'],
    ]);

    $result = \Cloudinary\Uploader::upload($filePath);
    return $result['secure_url'];
}
?>
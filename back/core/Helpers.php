<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

function getStatusColor($status) {
    return match($status) {
        'draft' => '#ffc107',
        'reviewing' => '#17a2b8',
        'private' => '#6c757d',
        'published' => '#28a745',
        'disqualified' => '#dc3545',
        default => '#6c757d'
    };
}

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
function generateUUID() {
    // Check if PHP has native UUID generation
    if (function_exists('random_bytes')) {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    // Fallback to mt_rand if random_bytes is not available
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

class Translate {
    private static $translations = [];
    private static $currentLang = 'fr';
    
    public static function init() {
        $langFile = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }
    
    public static function get($key, $default = null) {
        return self::$translations[$key] ?? $default ?? $key;
    }
    
    public static function setLang($lang) {
        self::$currentLang = $lang;
        self::init();
    }
    
    // Add this getter method
    public static function getCurrentLang() {
        return self::$currentLang;
    }
}
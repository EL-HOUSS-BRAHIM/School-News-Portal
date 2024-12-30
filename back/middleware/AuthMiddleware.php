<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private $secretKey = 'your_secret_key'; // Change this to a secure key

    public function handle() {
        if (!isset($_COOKIE['jwt'])) {
            header('Location: /login');
            exit();
        }

        try {
            $jwt = $_COOKIE['jwt'];
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            $_SESSION['user_id'] = $decoded->sub;
        } catch (Exception $e) {
            header('Location: /login');
            exit();
        }
    }
}
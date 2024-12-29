<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';  // Changed from ../../vendor to ../vendor

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController extends Controller
{
    private $secretKey = 'your_secret_key'; // Change this to a secure key


    public function login()
    {
    // Check if user is already logged in
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
            $this->redirect('/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $password = sanitizeInput($_POST['password']);

            error_log("Login attempt for username: " . $username);

            $userModel = new User();
            $user = $userModel->findByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                error_log("Login failed for user: " . $username);
                $this->renderView('auth/login', ['error' => 'Invalid credentials']);
                return;
            }

        // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
        
            error_log("Login successful. Session data: " . print_r($_SESSION, true));
        
        // Generate JWT token
            try {
                $payload = [
                    'iss' => 'yourdomain.com',
                    'iat' => time(),
                    'exp' => time() + (60 * 60),
                    'sub' => $user['id']
                ];
                $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
                setcookie('jwt', $jwt, time() + (60 * 60), '/', '', false, true);
            
                $this->redirect('/dashboard');
            } catch (Exception $e) {
                error_log("JWT Error: " . $e->getMessage());
                $this->renderView('auth/login', ['error' => 'Authentication failed']);
            }
        } else {
            $this->renderView('auth/login');
        }
    }

    public function logout()
    {
        setcookie('jwt', '', time() - 3600, '/');
        session_destroy();
        $this->redirect('/login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $password = password_hash(sanitizeInput($_POST['password']), PASSWORD_BCRYPT);

            $userModel = new User();
            $userModel->save([
                'username' => $username,
                'password' => $password,
            ]);

            $this->redirect('/login');
        } else {
            $this->renderView('auth/register');
        }
    }
}
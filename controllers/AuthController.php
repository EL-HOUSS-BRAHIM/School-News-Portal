<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Helpers.php';

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $password = sanitizeInput($_POST['password']);

            $userModel = new User();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $this->redirect('/admin');
            } else {
                $this->renderView('auth/login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->renderView('auth/login');
        }
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

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
?>
<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../core/Auth.php';

$auth = new Auth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($auth->login($username, $password)) {
            header('Location: ../public/index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }

    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            if ($auth->register($username, $password)) {
                header('Location: ../views/auth/login.php');
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        } else {
            $error = "Passwords do not match.";
        }
    }
}
?>
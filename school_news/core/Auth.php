<?php

class Auth {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function register($username, $password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare SQL statement
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public function login($username, $password) {
        // Prepare SQL statement
        $stmt = $this->db->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Start session and set user data
            session_start();
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
    }
}
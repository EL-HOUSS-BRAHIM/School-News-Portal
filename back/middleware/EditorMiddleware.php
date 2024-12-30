<?php

class EditorMiddleware {
    public function handle() {
        // Debug session
        error_log("Session data in middleware: " . print_r($_SESSION, true));

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            error_log("Missing session data. Redirecting to login.");
            header('Location: /login');
            exit();
        }

        if ($_SESSION['user_role'] !== 'editor' && $_SESSION['user_role'] !== 'admin') {
            error_log("Invalid role: " . $_SESSION['user_role']);
            header('Location: /login');
            exit();
        }
    }
}
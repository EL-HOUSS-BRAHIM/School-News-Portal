<?php

class EditorMiddleware {
    public function handle() {
        // Temporary bypass for testing
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = 1; // Temporary test user ID
            $_SESSION['user_role'] = 'editor'; // Temporary editor role
        }
        
        // Normal check (uncomment when ready for production)
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'editor') {
            header('Location: /login');
            exit();
        }
        */
    }
}
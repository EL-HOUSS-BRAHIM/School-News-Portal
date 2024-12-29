<?php

class EditorMiddleware {
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        if ($_SESSION['user_role'] !== 'editor' && $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}
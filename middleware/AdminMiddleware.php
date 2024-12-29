<?php
class AdminMiddleware {
    public function handle() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}
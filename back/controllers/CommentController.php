<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Comment.php';

class CommentController extends Controller {
    public function store()
{
    try {
        // Verify reCAPTCHA
        $recaptcha = $_POST['g-recaptcha-response'];
        $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'];
        
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptcha}");
        $captchaSuccess = json_decode($verify);
        
        if (!$captchaSuccess->success) {
            $_SESSION['error'] = 'Please verify that you are not a robot.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $data = [
            'article_id' => $_POST['article_id'],
            'name' => sanitizeInput($_POST['name']),
            'email' => sanitizeInput($_POST['email']),
            'content' => sanitizeInput($_POST['content']),
            'user_id' => $_SESSION['user_id'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $commentModel = new Comment();
        $commentModel->save($data);
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (Exception $e) {
        error_log("Comment Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error posting comment';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
}
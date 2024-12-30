<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Comment.php';

class CommentController extends Controller {
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        $articleId = $_POST['article_id'] ?? null;

        if (!$content || !$articleId) {
            $_SESSION['error'] = 'Comment content is required';
            header('Location: /article?id=' . $articleId);
            exit;
        }

        try {
            $commentModel = new Comment();
            $commentModel->save([
                'content' => $content,
                'article_id' => $articleId,
                'user_id' => $_SESSION['user_id']
            ]);
            
            header('Location: /article?id=' . $articleId);
        } catch (Exception $e) {
            error_log("Comment Error: " . $e->getMessage());
            $_SESSION['error'] = 'Error posting comment';
            header('Location: /article?id=' . $articleId);
        }
        exit;
    }
}
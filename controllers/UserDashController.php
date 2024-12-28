<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../middleware/EditorMiddleware.php';

class UserDashController extends Controller {
    private $middleware;

    public function __construct() {
        $this->middleware = new EditorMiddleware();
        $this->middleware->handle();
    }

    public function dashboard() {
        $articleModel = new Article();
        $commentModel = new Comment();

        $data = [
            'totalArticles' => $articleModel->countByUser($_SESSION['user_id']),
            'totalViews' => $articleModel->getTotalViewsByUser($_SESSION['user_id']),
            'totalLikes' => $articleModel->getTotalLikesByUser($_SESSION['user_id']),
            'totalComments' => $commentModel->countByUserArticles($_SESSION['user_id']),
            'recentArticles' => $articleModel->getByUser($_SESSION['user_id'], 5),
            'recentActivity' => $this->getRecentActivity($_SESSION['user_id'])
        ];

        $this->renderView('dash/index', $data);
    }

    public function articles() {
        $articleModel = new Article();
        $articles = $articleModel->getByUser($_SESSION['user_id']);
        $this->renderView('dash/articles', ['articles' => $articles]);
    }

    public function newArticle() {
        $this->renderView('dash/new-article');
    }

    public function editArticle($id) {
        $articleModel = new Article();
        $article = $articleModel->find($id);
        
        if ($article['user_id'] !== $_SESSION['user_id']) {
            $this->redirect('/dashboard');
        }
        
        $this->renderView('dash/edit-article', ['article' => $article]);
    }

    public function analytics() {
        $articleModel = new Article();
        $data = [
            'viewsChart' => $articleModel->getViewsStats($_SESSION['user_id']),
            'likesChart' => $articleModel->getLikesStats($_SESSION['user_id'])
        ];
        $this->renderView('dash/analytics', $data);
    }

    private function getRecentActivity($userId) {
        // Implementation for getting recent activity
        return [];
    }
}
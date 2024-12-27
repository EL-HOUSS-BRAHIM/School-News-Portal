<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';

class HomeController extends Controller
{
    public function index()
    {
        try {
            $articleModel = new Article();
            
            $data = [
                'featuredArticles' => $articleModel->getFeatured(5),
                'latestArticles' => $articleModel->getAll(4),
                'popularArticles' => $articleModel->getPopular(4),
                'sidebarArticles' => $articleModel->getPopular(5)
            ];
            
            $this->renderView('home/index', $data);
        } catch (Exception $e) {
            error_log("HomeController Error: " . $e->getMessage());
            $this->renderView('home/index', [
                'error' => 'Database error occurred. Please try again later.'
            ]);
        }
    }
}
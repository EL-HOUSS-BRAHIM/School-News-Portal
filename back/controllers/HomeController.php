<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController extends Controller
{
    public function index() {
        try {
            $categoryModel = new Category();
            $articleModel = new Article();
            
            $data = [
                'categories' => $categoryModel->getAll(),
                'breakingNews' => $articleModel->getBreakingNews(5),
                'mainSliderArticles' => $articleModel->getLatestFeatured(3),
                'featuredArticles' => $articleModel->getFeatured(8),
                'popularArticles' => $articleModel->getPopular(5),
                'latestArticles' => $articleModel->getLatest(6)
            ];
            
            // Load the view with data
            $this->renderView('home/index', $data);
            
        } catch (Exception $e) {
            error_log("Home Controller Error: " . $e->getMessage());
            $this->renderView('home/index', ['error' => 'An error occurred loading the page']);
        }
    }

    public function about() {
        try {
            $this->renderView('home/about');
        } catch (Exception $e) {
            error_log("About Page Error: " . $e->getMessage());
            $this->renderView('home/about', ['error' => 'An error occurred loading the page']);
        }
    }

    public function contact() {
        try {
            $this->renderView('home/contact');
        } catch (Exception $e) {
            error_log("Contact Page Error: " . $e->getMessage());
            $this->renderView('home/contact', ['error' => 'An error occurred loading the page']);
        }
    }

    private function decodeArticles($articles)
    {
        foreach ($articles as &$article) {
            if (isset($article['content'])) {
                $article['content'] = html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
        return $articles;
    }
}
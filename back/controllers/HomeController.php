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
                'mainSliderArticles' => $articleModel->getLatestFeatured(3, Article::STATUS_PUBLISHED) ?? [],
                'topArticles' => $articleModel->getLatest(4, Article::STATUS_PUBLISHED) ?? [], 
                'breakingNews' => $articleModel->getBreakingNews(5, Article::STATUS_PUBLISHED) ?? [],
                'featuredArticles' => $articleModel->getFeatured(8, Article::STATUS_PUBLISHED) ?? [],
                'latestArticles' => $articleModel->getAll(8, Article::STATUS_PUBLISHED) ?? [],
                'popularArticles' => $articleModel->getPopular(4, Article::STATUS_PUBLISHED) ?? []
            ];
            
            // Ensure all arrays are initialized
            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    $data[$key] = [];
                }
            }
            
            // Add error handling for empty data
            if (empty($data['mainSliderArticles']) && 
                empty($data['topArticles']) && 
                empty($data['breakingNews']) && 
                empty($data['featuredArticles'])) {
                error_log("No articles found in any category");
                $data['error'] = 'No articles available at the moment.';
            }
            
            $this->renderView('home/index', $data);
        } catch (Exception $e) {
            error_log("HomeController Error: " . $e->getMessage());
            $this->renderView('home/index', [
                'error' => 'Database error occurred. Please try again later.'
            ]);
        }
    }
}
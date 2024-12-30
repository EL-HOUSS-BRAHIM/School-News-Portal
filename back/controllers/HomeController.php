<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';

class HomeController extends Controller
{
    public function index()
    {
        try {
            $articleModel = new Article();
            
            // Debug database connection
            if (!$articleModel->hasConnection()) {
                error_log("No database connection");
                throw new Exception("Database connection failed");
            }
            
            // Get latest articles and decode their content
            $latestArticles = $articleModel->getAll(8);
            if ($latestArticles) {
                foreach ($latestArticles as &$article) {
                    if (isset($article['content'])) {
                        // First decode the double-encoded content
                        $article['content'] = html_entity_decode(html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        // Strip tags and limit the content length
                        $article['content'] = strip_tags($article['content']);
                        $article['content'] = substr($article['content'], 0, 150) . '...';
                    }
                }
            }
            
            // Get trending articles and decode their content
            $trendingArticles = $articleModel->getPopular(5);
            if ($trendingArticles) {
                foreach ($trendingArticles as &$article) {
                    if (isset($article['content'])) {
                        $article['content'] = html_entity_decode(html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $article['content'] = strip_tags($article['content']);
                        $article['content'] = substr($article['content'], 0, 150) . '...';
                    }
                }
            }
            
            $data = [
                'mainSliderArticles' => $this->decodeArticles($articleModel->getLatestFeatured(3, Article::STATUS_PUBLISHED) ?? []),
                'topArticles' => $this->decodeArticles($articleModel->getLatest(4, Article::STATUS_PUBLISHED) ?? []), 
                'breakingNews' => $this->decodeArticles($articleModel->getBreakingNews(5, Article::STATUS_PUBLISHED) ?? []),
                'featuredArticles' => $this->decodeArticles($articleModel->getFeatured(8, Article::STATUS_PUBLISHED) ?? []),
                'latestArticles' => $this->decodeArticles($articleModel->getAll(8, Article::STATUS_PUBLISHED) ?? []),
                'popularArticles' => $this->decodeArticles($articleModel->getPopular(4, Article::STATUS_PUBLISHED) ?? [])
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
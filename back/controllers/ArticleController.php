<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Ensure all necessary functions and constants are loaded

class ArticleController extends Controller
{
    public function index()
    {
        $articleModel = new Article();
        $articles = $articleModel->getAll();
        $this->renderView('article/list', ['articles' => $articles]);
    }

    public function create()
    {
        $this->renderView('article/create');
    }

    public function viewByTitle($title)
    {
        try {
            error_log("viewByTitle called with title: {$title}");
            $decodedTitle = urldecode($title);
            
            $articleModel = new Article();
            $article = $articleModel->getByTitle($decodedTitle);

            if (!$article) {
                error_log("Article not found for title: {$decodedTitle}");
                $this->redirect('/');
                return;
            }

            // Check if article status is published or user is admin
            if ($article['status'] !== 'published' && 
                (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
                error_log("Article not published and user not admin");
                $this->redirect('/');
                return;
            }

            // Increment view counter
            $articleModel->incrementViews($article['id']);

            // Get additional data
            $commentModel = new Comment();
            $data = [
                'article' => $article,
                'comments' => $commentModel->getByArticle($article['id']),
                'breakingNews' => $articleModel->getBreakingNews(5),
                'popularArticles' => $articleModel->getPopular(5),
                'relatedArticles' => $articleModel->getByCategory($article['category_id'], 3),
                'language' => $article['language']
            ];

            error_log("Rendering article view with data");
            $this->renderView('article/view', $data);

        } catch (Exception $e) {
            error_log("ArticleController::viewByTitle Error: " . $e->getMessage());
            $this->redirect('/');
        }
    }

    public function store(){
        try {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'], // No need to encode here, it will be encoded in the model
                'category_id' => $_POST['category_id'] ?? null,
                'language' => $_POST['language'] ?? 'fr',
                'user_id' => $_SESSION['user_id'],
                'status' => $_POST['status'] ?? 'draft',
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'breaking' => isset($_POST['breaking']) ? 1 : 0,
                'views' => 0,
                'likes' => 0
            ];
    
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
            }
    
            $articleModel = new Article();
            $result = $articleModel->save($data);
    
            if (!$result) {
                throw new Exception("Failed to save article");
            }
    
            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Error creating article: " . $e->getMessage());
            $this->redirect('/dashboard/article/new');
        }
    }
    
    public function update($id)
    {
        try {
            // Sanitize content but preserve HTML
            $_POST['content'] = filter_var($_POST['content'], FILTER_UNSAFE_RAW);
            
            $articleModel = new Article();
            $article = $articleModel->find($id);
    
            if (!$article) {
                throw new Exception("Article not found");
            }
    
            $updateData = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'category_id' => (int) $_POST['category_id'],
                'status' => $_POST['status'],
                'language' => $_POST['language'],
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'breaking' => isset($_POST['breaking']) ? 1 : 0
            ];
    
            $articleModel->update($id, $updateData);
            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Error updating article: " . $e->getMessage());
            $this->renderView('article/edit', [
                'error' => 'Failed to update article',
                'article' => $article
            ]);
        }
    }

    public function edit($id)
    {
        $articleModel = new Article();
        $article = $articleModel->find($id);
        $this->renderView('article/edit', ['article' => $article]);
    }

    public function delete($id)
    {
        try {
            $id = (int) $_POST['id']; // Change from $_GET to $_POST
            $articleModel = new Article();
            $article = $articleModel->find($id);

            // Verify ownership
            if ($article && $article['user_id'] === $_SESSION['user_id']) {
                $articleModel->delete($id);
            }

            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Error deleting article: " . $e->getMessage());
            $this->redirect('/dashboard/articles');
        }
    }

    public function view($id)
    {
        try {
            $articleModel = new Article();
            $article = $articleModel->getWithDetails($id);

            if (!$article) {
                return $this->renderView('article/view', ['article' => null]);
            }

            // Check article status
            if ($article['status'] !== Article::STATUS_PUBLISHED && 
                (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
                return $this->renderView('article/view', ['article' => null]);
            }

            $commentModel = new Comment();
            $data = [
                'article' => $article,
                'comments' => $commentModel->getByArticle($article['id']),
                'breakingNews' => $articleModel->getBreakingNews(5),
                'popularArticles' => $articleModel->getPopular(5),
                'relatedArticles' => $articleModel->getByCategory($article['category_id'], 3)
            ];

            $articleModel->incrementViews($article['id']);

            $this->renderView('article/view', $data);
        } catch (Exception $e) {
            error_log("ArticleController::view Error: " . $e->getMessage());
            $this->redirect('/');
        }
    }
}
<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Comment.php';

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
        error_log("viewByTitle called with title: " . $title);

        $articleModel = new Article();
        $article = $articleModel->getByTitle($title);

        error_log("Article data: " . print_r($article, true));

        if (!$article) {
            error_log("Article not found for title: " . $title);
            $this->redirect('/');
            return;
        }

        // Check if article status is published or user is admin
        if ($article['status'] !== Article::STATUS_PUBLISHED && 
            (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
            error_log("Article not published and user not admin");
            $this->redirect('/');
            return;
        }

        // Get breaking news
        $breakingNews = $articleModel->getBreakingNews(5);

        // Increment view counter
        $articleModel->incrementViews($article['id']);

        // Get comments
        $commentModel = new Comment();
        $comments = $commentModel->getByArticle($article['id']);

        error_log("Rendering article view with data");
        $this->renderView('article/view', [
            'article' => $article,
            'comments' => $comments,
            'breakingNews' => $breakingNews
        ]);

    } catch (Exception $e) {
        error_log("ArticleController::viewByTitle Error: " . $e->getMessage());
        $this->redirect('/');
    }
}

    public function store()
    {
        try {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'], // No need to strip tags here
                'category_id' => $_POST['category_id'],
                'user_id' => $_SESSION['user_id'],
                'status' => $_POST['status']
            ];
    
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
            }
    
            $articleModel = new Article();
            $articleModel->save($data);
    
            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Article Error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to create article";
            $this->redirect('/dashboard/article/new');
        }
    }
    
    public function update($id)
{
    try {
        $articleModel = new Article();
        $article = $articleModel->find($id);

        // Verify ownership if needed
        if (!$article) {
            throw new Exception("Article not found");
        }

        $updateData = [
            'title' => sanitizeInput($_POST['title']),
            'content' => $_POST['content'], // Pass raw content
            'category_id' => (int) $_POST['category_id'],
            'status' => $_POST['status']
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $updateData['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
        }

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
        $articleModel = new Article();
        $articleModel->delete($id);
        $this->redirect('/dashboard/articles');
    }


    public function view()
{
    try {
        $articleId = $_GET['id'] ?? null;

        if (!$articleId) {
            $this->redirect('/');
            return;
        }

        $articleModel = new Article();
        $article = $articleModel->getWithDetails($articleId);

        // Allow admins to view unpublished articles
        if (!$article || ($article['status'] !== Article::STATUS_PUBLISHED && $_SESSION['user_role'] !== 'admin')) {
            $this->redirect('/');
            return;
        }

        // Get breaking news
        $breakingNews = $articleModel->getBreakingNews(5);

        // Increment view counter
        $articleModel->incrementViews($articleId);

        // Get comments
        $commentModel = new Comment();
        $comments = $commentModel->getByArticle($articleId);

        $this->renderView('article/view', [
            'article' => $article,
            'comments' => $comments,
            'breakingNews' => $breakingNews
        ]);

    } catch (Exception $e) {
        error_log("ArticleController::view Error: " . $e->getMessage());
        $this->redirect('/');
    }
}
}
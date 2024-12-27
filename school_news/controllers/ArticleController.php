<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../core/Helpers.php';

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

    public function store()
    {
        $title = sanitizeInput($_POST['title']);
        $content = sanitizeInput($_POST['content']);
        $image = uploadToCloudinary($_FILES['image']['tmp_name']);

        $articleModel = new Article();
        $articleModel->save([
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ]);

        $this->redirect('/articles');
    }

    public function edit($id)
    {
        $articleModel = new Article();
        $article = $articleModel->find($id);
        $this->renderView('article/edit', ['article' => $article]);
    }

    public function update($id)
    {
        $title = sanitizeInput($_POST['title']);
        $content = sanitizeInput($_POST['content']);
        $image = uploadToCloudinary($_FILES['image']['tmp_name']);

        $articleModel = new Article();
        $articleModel->update($id, [
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ]);

        $this->redirect('/articles');
    }

    public function delete($id)
    {
        $articleModel = new Article();
        $articleModel->delete($id);
        $this->redirect('/articles');
    }

    // Add new view method
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
            
            if (!$article) {
                $this->redirect('/');
                return;
            }

            // Increment view counter
            $articleModel->incrementViews($articleId);
            
            // Get comments
            $commentModel = new Comment();
            $comments = $commentModel->getByArticle($articleId);
            
            $this->renderView('article/view', [
                'article' => $article,
                'comments' => $comments
            ]);
            
        } catch (Exception $e) {
            error_log("ArticleController::view Error: " . $e->getMessage());
            $this->redirect('/');
        }
    }
}
?>
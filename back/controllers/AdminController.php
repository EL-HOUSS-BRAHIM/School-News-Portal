<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

class AdminController extends Controller
{
    private $middleware;

    public function __construct() {
        $this->middleware = new AdminMiddleware();
        $this->middleware->handle();
    }

    public function index()
    {
        $userModel = new User();
        $articleModel = new Article();

        $users = $userModel->getAll();
        $articles = $articleModel->getAll();

        $this->renderView('admin/dashboard', [
            'users' => $users,
            'articles' => $articles,
        ]);
    }

    public function users()
    {
        $userModel = new User();
        $users = $userModel->getAll();
        $this->renderView('admin/users', ['users' => $users]);
    }

    public function addUser()
    {
        $this->renderView('admin/add_user');
    }

    public function storeUser()
    {
        $username = sanitizeInput($_POST['username']);
        $password = password_hash(sanitizeInput($_POST['password']), PASSWORD_BCRYPT);
        $role = sanitizeInput($_POST['role']);

        $userModel = new User();
        $userModel->save([
            'username' => $username,
            'password' => $password,
            'role' => $role,
        ]);

        $this->redirect('/admin/users');
    }

    public function editUser($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        $this->renderView('admin/edit_user', ['user' => $user]);
    }

    public function updateUser($id)
    {
        $username = sanitizeInput($_POST['username']);
        $password = password_hash(sanitizeInput($_POST['password']), PASSWORD_BCRYPT);

        $userModel = new User();
        $userModel->update($id, [
            'username' => $username,
            'password' => $password,
        ]);

        $this->redirect('/admin/users');
    }

    public function deleteUser($id)
    {
        $userModel = new User();
        $userModel->delete($id);
        $this->redirect('/admin/users');
    }

    public function review()
{
    try {
        $articleModel = new Article();
        $articles = $articleModel->getReviewArticles();
        
        $data = [
            'articles' => $articles,
            'currentPage' => 'review',
            'userData' => [
                'username' => $_SESSION['username'] ?? 'Admin',
                'role' => $_SESSION['user_role']
            ]
        ];
        
        $this->renderView('admin/review', $data);
    } catch (Exception $e) {
        error_log("Admin Review Error: " . $e->getMessage());
        $this->redirect('/admin/dashboard');
    }
}

public function publishArticle()
{
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception("Article ID not provided");
        }

        $articleModel = new Article();
        $articleModel->update($id, ['status' => Article::STATUS_PUBLISHED]);
        $this->redirect('/admin/review');
    } catch (Exception $e) {
        error_log("Publish Error: " . $e->getMessage());
        $this->redirect('/admin/review');
    }
}

public function rejectArticle()
{
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception("Article ID not provided");
        }

        $articleModel = new Article();
        $articleModel->update($id, ['status' => Article::STATUS_DISQUALIFIED]);
        $this->redirect('/admin/review');
    } catch (Exception $e) {
        error_log("Reject Error: " . $e->getMessage());
        $this->redirect('/admin/review');
    }
}
}
?>
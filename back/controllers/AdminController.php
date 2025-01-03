<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

require_once __DIR__ . '/../core/Helpers.php';

class AdminController extends Controller
{
    private $middleware;

    public function __construct() {
        $this->middleware = new AdminMiddleware();
        $this->middleware->handle();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function index()
{
    $userModel = new User();
    $articleModel = new Article();

    $users = $userModel->getAll();
    $articles = $articleModel->getAll();

    $userData = [
        'username' => $_SESSION['username'] ?? 'Admin',
        'avatar' => $_SESSION['avatar'] ?? '../assets/img/default-avatar.png',
        'notifications' => $this->getNotifications($_SESSION['user_id'])
    ];

    $this->renderView('admin/dashboard', [
        'users' => $users,
        'articles' => $articles,
        'userData' => $userData,
        'currentPage' => 'dashboard'
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
public function manageCategories()
{
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    $this->renderView('admin/manage_categories', ['categories' => $categories, 'currentPage' => 'manage_categories']);
}

public function storeCategory()
{
    $name = sanitizeInput($_POST['name']);
    $categoryModel = new Category();
    $categoryModel->save(['name' => $name]);
    $this->redirect('/admin/manage_categories');
}

public function deleteCategory($id = null)
{
    try {
        // Get ID from POST or GET if not passed as parameter
        $id = $id ?? ($_POST['id'] ?? $_GET['id'] ?? null);
        
        if (!$id) {
            throw new Exception('Category ID not provided');
        }

        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('CSRF token validation failed');
        }

        $categoryModel = new Category();
        $success = $categoryModel->delete($id);
        
        if ($success) {
            $_SESSION['success'] = 'Category deleted successfully. All associated articles have been moved to draft status.';
        } else {
            throw new Exception('Failed to delete category');
        }

        $this->redirect('/admin/manage_categories');
    } catch (Exception $e) {
        error_log("Delete Category Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error deleting category: ' . $e->getMessage();
        $this->redirect('/admin/manage_categories');
    }
}

public function uploadCategoryImage()
{
    try {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('CSRF token validation failed');
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No image uploaded');
        }

        $id = $_POST['id'];
        
        // Use the helper function to upload to Cloudinary
        $imageUrl = uploadToCloudinary($_FILES['image']['tmp_name']);

        $categoryModel = new Category();
        $categoryModel->uploadImage($id, $imageUrl);

        $this->redirect('/admin/manage_categories');
    } catch (Exception $e) {
        error_log("Upload Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error uploading image';
        $this->redirect('/admin/manage_categories');
    }
}

public function deleteCategoryImage()
{
    try {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('CSRF token validation failed');
        }

        $id = $_POST['id'];
        $categoryModel = new Category();
        $category = $categoryModel->find($id);

        if ($category && $category['image']) {
            // Configure Cloudinary
            $config = require __DIR__ . '/../config/cloudinary.php';
            
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => $config['cloud_name'],
                    'api_key' => $config['api_key'],
                    'api_secret' => $config['api_secret']
                ]
            ]);

            // Delete from Cloudinary using new SDK
            $uploadApi = new UploadApi();
            $publicId = $this->getPublicIdFromUrl($category['image']);
            if ($publicId) {
                $uploadApi->destroy($publicId);
            }
            
            // Update database
            $categoryModel->deleteImage($id);
        }

        $this->redirect('/admin/manage_categories');
    } catch (Exception $e) {
        error_log("Delete Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error deleting image';
        $this->redirect('/admin/manage_categories');
    }
}

private function getPublicIdFromUrl($url)
{
    // Extract public_id from Cloudinary URL
    preg_match('/\/v\d+\/(.+)\.\w+$/', $url, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}
public function listArticles() {
    try {
        $articleModel = new Article();
        
        // Update the SQL query to handle NULL values and ensure all fields are properly selected
        $sql = "SELECT 
                a.*,
                u.username as author,
                COALESCE(c.name, 'Unknown') as category,
                COALESCE(a.views, 0) as views,
                COALESCE(a.likes, 0) as likes,
                COALESCE((SELECT COUNT(*) FROM comments WHERE article_id = a.id), 0) as comments,
                COALESCE(a.status, 'draft') as status,
                COALESCE(a.created_at, NOW()) as created_at
                FROM articles a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                ORDER BY a.created_at DESC";
                
        $articles = $articleModel->query($sql);

        // Ensure all articles have required fields with default values
        $articles = array_map(function($article) {
            return array_merge([
                'id' => $article['id'] ?? '',
                'title' => $article['title'] ?? '',
                'content' => $article['content'] ?? '',
                'author' => $article['author'] ?? 'Unknown',
                'category' => $article['category'] ?? 'Unknown',
                'status' => $article['status'] ?? 'draft',
                'views' => (int)($article['views'] ?? 0),
                'likes' => (int)($article['likes'] ?? 0),
                'comments' => (int)($article['comments'] ?? 0),
                'created_at' => $article['created_at'] ?? date('Y-m-d H:i:s'),
                'image' => $article['image'] ?? '../assets/img/default-article.jpg'
            ], $article);
        }, $articles);

        $data = [
            'articles' => $articles,
            'currentPage' => 'all_articles',
            'userData' => [
                'username' => $_SESSION['username'] ?? 'Admin',
                'avatar' => $_SESSION['avatar'] ?? '../assets/img/default-avatar.png'
            ]
        ];

        $this->renderView('admin/list_articles', $data);
    } catch (Exception $e) {
        error_log("List Articles Error: " . $e->getMessage());
        $_SESSION['error'] = "Error loading articles";
        $this->redirect('/admin/dashboard');
    }
}

public function deleteArticle()
{
    try {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('CSRF token validation failed');
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            throw new Exception('Article ID not provided');
        }

        $articleModel = new Article();
        $success = $articleModel->delete($id);
        
        if ($success) {
            $_SESSION['success'] = 'Article deleted successfully';
        } else {
            throw new Exception('Failed to delete article');
        }

        $this->redirect('/admin/articles');
    } catch (Exception $e) {
        error_log("Delete Article Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error deleting article: ' . $e->getMessage();
        $this->redirect('/admin/articles');
    }
}

public function updateArticleStatus()
{
    try {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('CSRF token validation failed');
        }

        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            throw new Exception('Missing required parameters');
        }

        $articleModel = new Article();
        $success = $articleModel->update($id, ['status' => $status]);
        
        if ($success) {
            $_SESSION['success'] = 'Article status updated successfully';
        } else {
            throw new Exception('Failed to update article status');
        }

        $this->redirect('/admin/articles');
    } catch (Exception $e) {
        error_log("Update Status Error: " . $e->getMessage());
        $_SESSION['error'] = 'Error updating status: ' . $e->getMessage();
        $this->redirect('/admin/articles');
    }
}
}
?>
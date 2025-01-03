<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Category.php';  // Add this line
require_once __DIR__ . '/../middleware/EditorMiddleware.php';

class UserDashController extends Controller
{
    private $middleware;

    public function __construct()
    {
        $this->middleware = new EditorMiddleware();
        $this->middleware->handle();
    }

    public function dashboard()
{
    $articleModel = new Article();
    $commentModel = new Comment();

    // Get statistics
    $totalArticles = $articleModel->countByUser($_SESSION['user_id']);
    $totalViews = $articleModel->getTotalViewsByUser($_SESSION['user_id']);
    $totalLikes = $articleModel->getTotalLikesByUser($_SESSION['user_id']);
    $totalComments = $commentModel->countByUserArticles($_SESSION['user_id']);

    // Get recent articles
    $recentArticles = $articleModel->getByUser($_SESSION['user_id'], 5);

    // Get performance data for chart
    $viewsData = $articleModel->getViewsStats($_SESSION['user_id']);
    $likesData = $articleModel->getLikesStats($_SESSION['user_id']);

    // Get recent activity
    $recentActivity = $this->getRecentActivity($_SESSION['user_id']);

    $data = [
        'totalArticles' => $totalArticles,
        'totalViews' => $totalViews,
        'totalLikes' => $totalLikes,
        'totalComments' => $totalComments,
        'recentArticles' => $recentArticles,
        'viewsData' => $viewsData,
        'likesData' => $likesData,
        'recentActivity' => $recentActivity
    ];

    // Get user data
    $userData = [
        'username' => $_SESSION['username'] ?? 'User',
        'avatar' => $_SESSION['avatar'] ?? '../assets/img/default-avatar.png',
        'notifications' => $this->getNotifications($_SESSION['user_id'])
    ];

    $data = array_merge($data, [
        'userData' => $userData,
        'currentPage' => 'dashboard'
    ]);

    $this->renderView('dash/index', $data);
}


public function articles()
{
    try {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $articleModel = new Article();
        $categoryModel = new Category();

        // Get articles with all necessary fields
        $sql = "SELECT 
                a.*,
                c.name as category_name,
                u.username as author_name,
                COALESCE(
                    (SELECT COUNT(*) FROM comments WHERE article_id = a.id), 
                    0
                ) as comment_count
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.user_id = :user_id
            ORDER BY a.created_at DESC";

        $articles = $articleModel->query($sql, [':user_id' => $_SESSION['user_id']]);
        $categories = $categoryModel->getAll();

        $data = [
            'articles' => $articles,
            'categories' => $categories,
            'userData' => [
                'username' => $_SESSION['username'],
                'role' => $_SESSION['user_role']
            ],
            'currentPage' => 'articles'
        ];

        $this->renderView('article/list', $data);
    } catch (Exception $e) {
        error_log("Error in articles method: " . $e->getMessage());
        $this->redirect('/login');
    }
}


public function index()
{
    try {
        $articleModel = new Article();

        $data = [
            'mainSliderArticles' => $articleModel->getLatestFeatured(3, 'published') ?? [],
            'topArticles' => $articleModel->getLatest(4, 'published') ?? [],
            'breakingNews' => $articleModel->getBreakingNews(5, 'published') ?? [],
            'featuredArticles' => $articleModel->getFeatured(8, 'published') ?? [],
            'latestArticles' => $articleModel->getAll(8, 'published') ?? [],
            'popularArticles' => $articleModel->getPopular(4, 'published') ?? []
        ];

        // Add language filter if needed
        $currentLang = Translate::getCurrentLang();
        foreach ($data as $key => $articles) {
            $data[$key] = array_filter($articles, function($article) use ($currentLang) {
                return $article['language'] === $currentLang;
            });
        }

        $this->renderView('home/index', $data);
    } catch (Exception $e) {
        error_log("HomeController Error: " . $e->getMessage());
        $this->renderView('home/index', [
            'error' => 'Database error occurred. Please try again later.'
        ]);
    }
}

    public function newArticle()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        $data = [
            'categories' => $categories,
            'userData' => [
                'username' => $_SESSION['username'] ?? 'User',
                'avatar' => $_SESSION['avatar'] ?? '../assets/img/default-avatar.png'
            ],
            'currentPage' => 'new'  // Make sure this matches the sidenav condition
        ];

        error_log("Current Page: " . $data['currentPage']);  // Add this line for debugging

        $this->renderView('article/create', $data);
    }


    public function storeArticle()
{
    try {
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'category_id' => $_POST['category_id'],
            'language' => $_POST['language'],
            'user_id' => $_SESSION['user_id'],
            'status' => $_POST['status'],
            'featured' => isset($_POST['featured']) && $_POST['featured'] == '1' ? 1 : 0,
            'breaking' => isset($_POST['breaking']) && $_POST['breaking'] == '1' ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'views' => 0,
            'likes' => 0
        ];

        // Debug logging
        error_log("POST data: " . print_r($_POST, true));
        error_log("Processed data: " . print_r($data, true));

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
        }

        $articleModel = new Article();
        if ($articleModel->save($data)) {
            $this->redirect('/dashboard/articles');
        } else {
            throw new Exception("Failed to save article");
        }
    } catch (Exception $e) {
        error_log("Error in storeArticle: " . $e->getMessage());
        $this->redirect('/dashboard/article/new');
    }
}

public function updateArticle()
{
    try {
        $id = (int)$_POST['id'];
        $articleModel = new Article();
        $article = $articleModel->find($id);

        if (!$article || $article['user_id'] !== $_SESSION['user_id']) {
            $this->redirect('/dashboard/articles');
            return;
        }

        $updateData = [
            'title' => sanitizeInput($_POST['title']),
            'content' => sanitizeInput($_POST['content']),
            'category_id' => (int)$_POST['category_id'],
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'breaking' => isset($_POST['breaking']) ? 1 : 0,
            'status' => $_POST['status'],
            'language' => $_POST['language'] ?? 'fr'
        ];

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $updateData['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
        }

        $articleModel->update($id, $updateData);
        $this->redirect('/dashboard/articles');

    } catch (Exception $e) {
        error_log("Error updating article: " . $e->getMessage());
        $this->redirect('/dashboard/articles');
    }
}

    public function editArticle()
    {
        $id = (int) $_GET['id'];
        $articleModel = new Article();
        $categoryModel = new Category();

        $article = $articleModel->find($id);

        // Check if article exists and belongs to user
        if (!$article || $article['user_id'] !== $_SESSION['user_id']) {
            $this->redirect('/dashboard/articles');
            return;
        }

        $categories = $categoryModel->getAll();

        $data = [
            'article' => $article,
            'categories' => $categories,
            'userData' => [
                'username' => $_SESSION['username'] ?? 'User',
                'avatar' => $_SESSION['avatar'] ?? '../assets/img/default-avatar.png'
            ],
            'currentPage' => 'articles'
        ];

        $this->renderView('article/edit', $data);
    }

    public function updateStatus() 
{
    try {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception("CSRF token validation failed");
        }

        $id = $_POST['id'];
        $newStatus = $_POST['status'];
        $userRole = $_SESSION['user_role'];
        
        $articleModel = new Article();
        $article = $articleModel->find($id);

        if (!$article) {
            throw new Exception("Article not found");
        }

        // Check permissions
        $allowedStatuses = Article::getAvailableStatuses($userRole);
        if (!array_key_exists($newStatus, $allowedStatuses)) {
            throw new Exception("Status change not allowed");
        }

        // Verify ownership
        if ($article['user_id'] != $_SESSION['user_id'] && $userRole !== 'admin') {
            throw new Exception("Unauthorized access");
        }

        if ($articleModel->update($id, ['status' => $newStatus])) {
            $_SESSION['success'] = "Status updated successfully";
        } else {
            throw new Exception("Failed to update status");
        }

        $this->redirect('/dashboard/articles');
    } catch (Exception $e) {
        error_log("Status update error: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        $this->redirect('/dashboard/articles');
    }
}

public function deleteArticle()
{
    try {
        // Check if article ID is provided
        if (!isset($_POST['id'])) {
            throw new Exception("Article ID not provided");
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token'])) {
            throw new Exception("CSRF token not provided");
        }

        if (!isset($_SESSION['csrf_token'])) {
            throw new Exception("Session CSRF token not found");
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception("Invalid CSRF token");
        }

        $id = $_POST['id'];
        $articleModel = new Article();
        $article = $articleModel->find($id);

        // Verify article exists
        if (!$article) {
            throw new Exception("Article not found");
        }

        // Verify ownership or admin rights
        if ($article['user_id'] !== $_SESSION['user_id'] && $_SESSION['user_role'] !== 'admin') {
            throw new Exception("Unauthorized access");
        }

        // Delete the article
        if (!$articleModel->delete($id)) {
            throw new Exception("Failed to delete article");
        }

        $_SESSION['success'] = "Article deleted successfully";
        $this->redirect('/dashboard/articles');
    } catch (Exception $e) {
        error_log("Error deleting article: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        $this->redirect('/dashboard/articles');
    }
}


    private function getRecentActivity($userId)
    {
        // Implementation for getting recent activity
        return [];
    }

    private function getNotifications($userId)
    {
        // Example notifications - replace with actual DB query
        return [
            [
                'type' => 'message',
                'title' => 'New Comment',
                'content' => 'Someone commented on your article',
                'time' => '13 minutes ago',
                'icon' => '../assets/img/team-2.jpg',
                'link' => '#'
            ],
            // Add more notifications as needed
        ];
    }
    
    public function analytics()
    {
        try {
            $articleModel = new Article();
            $userId = $_SESSION['user_id'];
    
            // Get metrics data
            $metrics = [
                'total_views' => $articleModel->getTotalViewsByUser($userId),
                'total_engagement' => $articleModel->getTotalEngagementByUser($userId),
                'views_trend' => $articleModel->getViewsTrend($userId),
                'engagement_trend' => $articleModel->getEngagementTrend($userId),
                'engagement_rate' => $articleModel->getEngagementRate($userId),
                'top_articles' => $articleModel->getTopArticles($userId),
                'performance_data' => [
                    'labels' => $articleModel->getPerformanceLabels($userId),
                    'views' => $articleModel->getViewsData($userId),
                    'engagement' => $articleModel->getEngagementData($userId)
                ]
            ];
    
            $data = [
                'metrics' => $metrics,
                'currentPage' => 'analytics',
                'userData' => [
                    'username' => $_SESSION['username'],
                    'role' => $_SESSION['user_role']
                ]
            ];
    
            $this->renderView('dash/analytics', $data);
        } catch (Exception $e) {
            error_log("Analytics Error: " . $e->getMessage());
            $this->redirect('/dashboard');
        }
    }

public function profile()
{
    try {
        $userModel = new User();
        $articleModel = new Article();
        $userId = $_SESSION['user_id'];

        // Get user data with null checks
        $user = $userModel->find($userId);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Get user stats
        $stats = [
            'total_articles' => $articleModel->countByUser($userId) ?? 0,
            'total_views' => $articleModel->getTotalViewsByUser($userId) ?? 0,
            'total_likes' => $articleModel->getTotalLikesByUser($userId) ?? 0,
            'avg_engagement' => $articleModel->getEngagementRate($userId) ?? 0
        ];

        $data = [
            'userData' => [
                'username' => $user['username'] ?? 'Unknown',
                'email' => $user['email'] ?? 'No email',
                'role' => $user['role'] ?? 'user',
                'joined_date' => $user['created_at'] ?? date('Y-m-d H:i:s'),
                'avatar' => $user['avatar'] ?? '../assets/img/default-avatar.png'
            ],
            'stats' => $stats,
            'currentPage' => 'profile'
        ];

        $this->renderView('dash/profile', $data);
    } catch (Exception $e) {
        error_log("Profile Error: " . $e->getMessage());
        $this->redirect('/dashboard');
    }
}

public function updateProfile()
{
    try {
        $userId = $_SESSION['user_id'];
        $userModel = new User();

        if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = uploadToCloudinary($_FILES['avatar']['tmp_name']);
            $userModel->update($userId, ['avatar' => $avatar]);
        }

        $this->redirect('/dashboard/profile');
    } catch (Exception $e) {
        error_log("Profile Update Error: " . $e->getMessage());
        $this->redirect('/dashboard/profile');
    }
}

public function updatePassword()
{
    try {
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->find($userId);

        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $user['password'])) {
            throw new Exception("Current password is incorrect");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("New passwords don't match");
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel->update($userId, ['password' => $hashedPassword]);

        $this->redirect('/dashboard/profile');
    } catch (Exception $e) {
        error_log("Password Update Error: " . $e->getMessage());
        $this->redirect('/dashboard/profile');
    }
}
}
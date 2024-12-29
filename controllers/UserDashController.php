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
                error_log("No user_id in session for articles page");
                $this->redirect('/login');
                return;
            }

            $articleModel = new Article();
            $articles = $articleModel->getByUser($_SESSION['user_id']);
        
            $data = [
                'articles' => $articles,
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
            if (
                empty($data['mainSliderArticles']) &&
                empty($data['topArticles']) &&
                empty($data['breakingNews']) &&
                empty($data['featuredArticles'])
            ) {
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
            $status = in_array($_POST['status'], [Article::STATUS_DRAFT, Article::STATUS_REVIEWING, Article::STATUS_PRIVATE]) ? $_POST['status'] : Article::STATUS_DRAFT;

            $data = [
                'title' => sanitizeInput($_POST['title']),
                'content' => sanitizeInput($_POST['content']),
                'category_id' => (int) $_POST['category_id'],
                'user_id' => $_SESSION['user_id'],
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
            }

            $articleModel = new Article();
            $articleModel->save($data);

            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Error creating article: " . $e->getMessage());
            $this->renderView('article/create', [
                'error' => 'Failed to create article',
                'formData' => $_POST
            ]);
        }
    }

    public function updateArticle()
    {
        try {
            $id = (int) $_POST['id'];
            $articleModel = new Article();
            $article = $articleModel->find($id);

            // Verify ownership
            if (!$article || $article['user_id'] !== $_SESSION['user_id']) {
                $this->redirect('/dashboard/articles');
                return;
            }

            $status = in_array($_POST['status'], [Article::STATUS_DRAFT, Article::STATUS_REVIEWING, Article::STATUS_PRIVATE]) ? $_POST['status'] : $article['status'];

            $updateData = [
                'title' => sanitizeInput($_POST['title']),
                'content' => sanitizeInput($_POST['content']),
                'category_id' => (int) $_POST['category_id'],
                'status' => $status
            ];

            // Handle image upload if new image provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $updateData['image'] = uploadToCloudinary($_FILES['image']['tmp_name']);
            }

            $articleModel->update($id, $updateData);
            $this->redirect('/dashboard/articles');

        } catch (Exception $e) {
            error_log("Error updating article: " . $e->getMessage());
            $this->renderView('article/edit', [
                'error' => 'Failed to update article',
                'article' => $articleModel->find($id)
            ]);
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
            if (!in_array($_POST['status'], array_keys(Article::getAllStatuses()))) {
                throw new Exception("Invalid status");
            }

            $id = (int) $_POST['id'];
            $articleModel = new Article();
            $article = $articleModel->find($id);

            if (!$article || $article['user_id'] !== $_SESSION['user_id']) {
                throw new Exception("Unauthorized");
            }

            $articleModel->update($id, ['status' => $_POST['status']]);

            $this->redirect('/dashboard/articles');
        } catch (Exception $e) {
            error_log("Error updating status: " . $e->getMessage());
            $this->redirect('/dashboard/articles');
        }
    }

    public function deleteArticle()
    {
        try {
            $id = (int) $_GET['id'];
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

    public function analytics()
    {
        $articleModel = new Article();
        $data = [
            'viewsChart' => $articleModel->getViewsStats($_SESSION['user_id']),
            'likesChart' => $articleModel->getLikesStats($_SESSION['user_id'])
        ];
        $this->renderView('dash/analytics', $data);
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
}
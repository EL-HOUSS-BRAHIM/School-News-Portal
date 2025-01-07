<?php
$routes = [
    '/' => 'HomeController@index',
    '/article' => 'ArticleController@view',
    '/article/{title}' => 'ArticleController@viewByTitle',
    '/login' => 'AuthController@login',
    '/register' => 'AuthController@register',
    '/logout' => 'AuthController@logout',
    '/admin' => 'AdminController@index',
    '/comment/add' => 'CommentController@store',
    
    // Dashboard article management routes
    '/dashboard' => 'UserDashController@dashboard',
    '/dashboard/articles' => 'UserDashController@articles',
    '/dashboard/article/new' => 'UserDashController@newArticle',
    '/dashboard/article/edit' => 'UserDashController@editArticle',
    '/dashboard/article/update' => 'UserDashController@updateArticle',
    '/dashboard/article/delete' => 'UserDashController@deleteArticle',
    '/dashboard/article/store' => 'UserDashController@storeArticle',
    '/dashboard/article/status' => 'UserDashController@updateStatus',
    '/dashboard/profile' => 'UserDashController@profile',
    '/dashboard/profile/update' => 'UserDashController@updateProfile',
    '/dashboard/profile/password' => 'UserDashController@updatePassword',
    '/dashboard/analytics' => 'UserDashController@analytics',

    // Admin routes
    '/admin/users' => 'AdminController@users',
    '/admin/add_user' => 'AdminController@addUser',
    '/admin/store_user' => 'AdminController@storeUser',
    '/admin/review' => 'AdminController@review',
    '/admin/publish_article' => 'AdminController@publishArticle',
    '/admin/all_articles' => 'AdminController@listArticles',
    '/admin/article/delete' => 'AdminController@deleteArticle',
    '/admin/article/status' => 'AdminController@updateArticleStatus',
    '/admin/reject_article' => 'AdminController@rejectArticle',
    '/admin/manage_categories' => 'AdminController@manageCategories',
    '/admin/store_category' => 'AdminController@storeCategory',
    '/admin/delete_category' => 'AdminController@deleteCategory',
    '/admin/upload_category_image' => 'AdminController@uploadCategoryImage',
    '/admin/delete_category_image' => 'AdminController@deleteCategoryImage',

    '/upload/image' => 'UploadController@uploadImage',

    // New routes for about and contact pages
    '/about' => 'HomeController@about',
    '/contact' => 'HomeController@contact',
];

function getRoute($url) {
    global $routes;
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];
    $query = [];
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $query);
    }

    error_log("Processing URL: " . $path);

    // Handle dynamic routes
    foreach ($routes as $route => $controllerAction) {
        $pattern = str_replace('/', '\/', $route);
        $pattern = preg_replace('/\{[^\}]+\}/', '([^\/]+)', $pattern);
        $pattern = "/^" . $pattern . "$/";
        
        error_log("Checking pattern: " . $pattern . " against path: " . $path);
        
        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches); // Remove the full match
            error_log("Route matched. Controller action: " . $controllerAction);
            error_log("Parameters: " . print_r($matches, true));
            return [$controllerAction, $matches];
        }
    }

    error_log("No route matched. Falling back to: " . ($routes[$path] ?? 'null'));
    return [$routes[$path] ?? null, $query];
}
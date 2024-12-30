<?php
$routes = [
    '/' => 'HomeController@index',
    '/articles' => 'ArticleController@index',
    '/article' => 'ArticleController@view',
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
    '/admin/reject_article' => 'AdminController@rejectArticle',
];

function getRoute($url) {
    global $routes;
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];
    $query = [];
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $query);
    }
    return [$routes[$path] ?? null, $query];
}
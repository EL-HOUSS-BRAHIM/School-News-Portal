<?php
$routes = [
    '/' => 'HomeController@index',
    '/articles' => 'ArticleController@index',
    '/article' => 'ArticleController@view',
    '/login' => 'AuthController@login',
    '/register' => 'AuthController@register',
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
    '/dashboard/analytics' => 'UserDashController@analytics',
    '/dashboard/profile' => 'UserDashController@profile',
    '/logout' => 'AuthController@logout'
];

function getRoute($url) {
    global $routes;
    return $routes[$url] ?? null;
}
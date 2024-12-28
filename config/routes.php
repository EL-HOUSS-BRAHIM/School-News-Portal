<?php
$routes = [
    // Existing routes
    '/' => 'HomeController@index',
    '/articles' => 'ArticleController@index',
    '/article' => 'ArticleController@view',
    '/articles/create' => 'ArticleController@create',
    '/articles/store' => 'ArticleController@store',
    '/articles/edit' => 'ArticleController@edit',
    '/articles/update' => 'ArticleController@update',
    '/articles/delete' => 'ArticleController@delete',
    '/login' => 'AuthController@login',
    '/register' => 'AuthController@register',
    '/admin' => 'AdminController@index',
    '/comment/add' => 'CommentController@store',
    
    // New dashboard routes
    '/dashboard' => 'UserDashController@dashboard',
    '/dashboard/articles' => 'UserDashController@articles',
    '/dashboard/article/new' => 'UserDashController@newArticle',
    '/dashboard/article/edit' => 'UserDashController@editArticle',
    '/dashboard/analytics' => 'UserDashController@analytics',
    '/dashboard/profile' => 'UserDashController@profile',
    '/logout' => 'AuthController@logout'
];

function getRoute($url) {
    global $routes;
    return $routes[$url] ?? null;
}
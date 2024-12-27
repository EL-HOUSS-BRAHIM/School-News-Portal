<?php
$routes = [
    '/' => 'HomeController@index',
    '/articles' => 'ArticleController@index',
    '/article' => 'ArticleController@view', // Add this route
    '/articles/create' => 'ArticleController@create',
    '/articles/store' => 'ArticleController@store',
    '/articles/edit' => 'ArticleController@edit',
    '/articles/update' => 'ArticleController@update',
    '/articles/delete' => 'ArticleController@delete',
    '/login' => 'AuthController@login',
    '/register' => 'AuthController@register',
    '/admin' => 'AdminController@index',
    '/comment/add' => 'CommentController@store',
];

function getRoute($url) {
    global $routes;
    return $routes[$url] ?? null;
}
?>
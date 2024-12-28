<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/ArticleController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/CommentController.php';
require_once __DIR__ . '/../controllers/UserDashController.php';

try {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = getRoute($requestUri);
    
    if ($route) {
        list($controller, $method) = explode('@', $route);
        $controllerClass = new $controller();
        $controllerClass->$method();
    } else {
        // Default to home
        $controller = new HomeController();
        $controller->index();
    }
} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    echo "An error occurred. Please check the error logs.";
}
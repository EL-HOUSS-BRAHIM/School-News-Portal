<?php
// filepath: /home/brahim/Desktop/newproject/public/index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Debug env loading
    error_log("Current directory: " . __DIR__);
    error_log("Loading .env from: " . __DIR__ . '/..');
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    // Immediately verify env values 
    error_log("Environment values after loading:");
    error_log("DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set'));
    error_log("DB_NAME: " . ($_ENV['DB_NAME'] ?? 'not set'));
    error_log("DB_USER: " . ($_ENV['DB_USER'] ?? 'not set'));
    
} catch (Exception $e) {
    error_log("Dotenv Error: " . $e->getMessage());
    die("Environment configuration error");
}

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
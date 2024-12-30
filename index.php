<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/back/middleware/ApiSecurityMiddleware.php';
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$security = new ApiSecurityMiddleware();
$security->handle();
require_once __DIR__ . '/back/vendor/autoload.php';

try {
    // Debug env loading
    error_log("Current directory: " . __DIR__);
    error_log("Loading .env from: " . __DIR__ . '/back');
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/back');
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

require_once __DIR__ . '/back/config/routes.php';
require_once __DIR__ . '/back/core/Model.php';
require_once __DIR__ . '/back/core/Controller.php';
require_once __DIR__ . '/back/controllers/HomeController.php';
require_once __DIR__ . '/back/controllers/ArticleController.php';
require_once __DIR__ . '/back/controllers/AuthController.php';
require_once __DIR__ . '/back/controllers/AdminController.php';
require_once __DIR__ . '/back/controllers/CommentController.php';
require_once __DIR__ . '/back/controllers/UserDashController.php';

try {
    $url = $_SERVER['REQUEST_URI'];
    list($route, $params) = getRoute($url);

    if ($route) {
        list($controllerName, $methodName) = explode('@', $route);
        require_once __DIR__ . "/back/controllers/$controllerName.php";
        $controller = new $controllerName();

        if (method_exists($controller, $methodName)) {
            // Convert associative array to indexed array
            $params = array_values($params);
            call_user_func_array([$controller, $methodName], $params);
        } else {
            echo "Method $methodName not found in controller $controllerName.";
        }
    } else {
        echo "Route not found.";
    }
} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    echo "An error occurred. Please check the error logs.";
}
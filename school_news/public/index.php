<?php
// Main entry point of the application

// Load configuration
require_once '../config/config.php';
require_once '../config/database.php';

// Include core classes
require_once '../core/Auth.php';
require_once '../core/Article.php';
require_once '../core/Category.php';
require_once '../core/Comment.php';
require_once '../core/User.php';

// Start session
session_start();

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Load header
include '../includes/header.php';

// Load the main content
// Here you can include the main view or route to different functionalities based on the request
include '../views/articles/list.php'; // Example: Load the article list view

// Load footer
include '../includes/footer.php';
?>
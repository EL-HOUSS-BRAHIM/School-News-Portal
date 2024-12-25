<?php
// single.php - Displays a single article view

// Include necessary files
include_once '../../config/config.php';
include_once '../../config/database.php';
include_once '../../core/Article.php';
include_once '../../includes/header.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create an instance of the Article class
$article = new Article($db);

// Get the article ID from the URL
$article_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Article ID not found.');

// Fetch the article details
$article->id = $article_id;
$article->readOne();

// Check if article exists
if($article->title != null){
    // Display article details
    echo "<h1>{$article->title}</h1>";
    echo "<p>{$article->content}</p>";
    echo "<p><strong>Category:</strong> {$article->category_id}</p>";
    echo "<p><strong>Published on:</strong> {$article->created_at}</p>";
} else {
    echo "<p>Article not found.</p>";
}

// Include footer
include_once '../../includes/footer.php';
?>
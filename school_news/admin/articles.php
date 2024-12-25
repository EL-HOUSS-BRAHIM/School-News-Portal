<?php
// articles.php - Manages article-related administrative functions

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../core/Article.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create an instance of the Article class
$article = new Article($db);

// Handle various article-related actions (e.g., create, update, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: Create a new article
    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $article->create($title, $content);
    }
    // Additional actions (update, delete) can be handled here
}

// Fetch all articles for display
$articles = $article->getAllArticles();

include '../includes/header.php';
?>

<h1>Manage Articles</h1>

<!-- Article creation form -->
<form method="POST" action="">
    <input type="text" name="title" placeholder="Article Title" required>
    <textarea name="content" placeholder="Article Content" required></textarea>
    <button type="submit" name="create">Create Article</button>
</form>

<!-- Display articles -->
<h2>Existing Articles</h2>
<ul>
    <?php foreach ($articles as $article): ?>
        <li><?php echo htmlspecialchars($article['title']); ?></li>
    <?php endforeach; ?>
</ul>

<?php include '../includes/footer.php'; ?>
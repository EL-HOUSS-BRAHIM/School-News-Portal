<?php
// category.php

// Include necessary files
require_once '../../config/config.php';
require_once '../../core/Article.php';
require_once '../../core/Category.php';

// Initialize the Article and Category classes
$article = new Article();
$category = new Category();

// Get the category ID from the URL
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch articles by category
$articles = $article->getArticlesByCategory($categoryId);

// Fetch category details
$categoryDetails = $category->getCategory($categoryId);

// Include header
include '../../includes/header.php';
?>

<div class="container">
    <h1><?php echo htmlspecialchars($categoryDetails['name']); ?> Articles</h1>
    
    <?php if (!empty($articles)): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <a href="single.php?id=<?php echo $article['id']; ?>">
                        <?php echo htmlspecialchars($article['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No articles found in this category.</p>
    <?php endif; ?>
</div>

<?php
// Include footer
include '../../includes/footer.php';
?>
<?php
// Fetch articles from the database
require_once '../../config/database.php';
require_once '../../core/Article.php';

$articleModel = new Article();
$articles = $articleModel->getAllArticles();

include '../../includes/header.php';
?>

<div class="container">
    <h1>Articles</h1>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <a href="single.php?id=<?php echo $article['id']; ?>">
                    <?php echo htmlspecialchars($article['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include '../../includes/footer.php'; ?>
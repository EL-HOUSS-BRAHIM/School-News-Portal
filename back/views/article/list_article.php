<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                             class="card-img-top" alt="Article image">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/article/<?php echo urlencode($article['title']); ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text">
                                <?php 
                                $content = strip_tags($article['content']);
                                echo htmlspecialchars(substr($content, 0, 200)) . '...'; 
                                ?>
                            </p>
                            <div class="meta-info">
                                <small>
                                    Category: <?php echo htmlspecialchars($article['category']); ?> | 
                                    Posted: <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p>No articles found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
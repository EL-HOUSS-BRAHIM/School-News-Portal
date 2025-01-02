<?php require_once __DIR__ . '/../layouts/article_header.php'; ?>
<?php
$recaptchaKey = $_ENV['RECAPTCHA_SITE_KEY'];
?>

<!-- News With Sidebar Start -->
<div class="container-fluid py-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (isset($article) && $article): ?>
                    <!-- News Detail Start -->
                    <div class="position-relative mb-3">
                        <img class="img-fluid w-100" 
                             src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                             style="object-fit: cover;">
                        <div class="overlay position-relative bg-light">
                            <div class="mb-3">
                                <a href="/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                    <?php echo htmlspecialchars($article['category']); ?>
                                </a>
                                <span class="px-1">/</span>
                                <span><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <div>
                                <h3 class="mb-3"><?php echo htmlspecialchars($article['title']); ?></h3>
                                <div class="article-content">
                                    <?php echo $article['content']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- News Detail End -->

                    <!-- Comment List Start -->
                    <div class="bg-light mb-3" style="padding: 30px;">
                        <h3 class="mb-4"><?php echo count($comments); ?> Comments</h3>
                        <?php foreach($comments as $comment): ?>
                            <div class="media mb-4">
                                <img src="/img/user.jpg" alt="User" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                <div class="media-body">
                                    <h6>
                                        <?php echo htmlspecialchars($comment['name']); ?>
                                        <small><i><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></i></small>
                                    </h6>
                                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Comment List End -->

                    <!-- Comment Form Start -->
                    <div class="bg-light mb-3" style="padding: 30px;">
                        <h3 class="mb-4">Leave a comment</h3>
                        <form action="/comment/add" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">
                            
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="content" cols="30" rows="5" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($recaptchaKey); ?>"></div>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary font-weight-semi-bold py-2 px-3">
                                    Leave a comment
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Comment Form End -->
                <?php else: ?>
                    <div class="alert alert-warning">Article not found</div>
                <?php endif; ?>
            </div>

            <?php require_once __DIR__ . '/../layouts/article_sidebar.php'; ?>
        </div>
    </div>
</div>
<!-- News With Sidebar End -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<!-- Add reCAPTCHA Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- News Article Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "<?php echo htmlspecialchars($article['title']); ?>",
    "image": ["<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>"],
    "datePublished": "<?php echo date('c', strtotime($article['created_at'])); ?>",
    "dateModified": "<?php echo date('c', strtotime($article['updated_at'] ?? $article['created_at'])); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo htmlspecialchars($article['author'] ?? 'Anonymous'); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "College Dar Bouazza News",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo $app['constants']['ASSETS_URL']; ?>/img/logo.png"
        }
    },
    "description": "<?php echo htmlspecialchars($article['description'] ?? ''); ?>"
}
</script>
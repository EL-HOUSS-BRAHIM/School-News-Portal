<?php require_once __DIR__ . '/../layouts/article_header.php'; ?>
<?php
$recaptchaKey = $_ENV['RECAPTCHA_SITE_KEY'];
?>
<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../config/app.php';

try {
    $articleModel = new Article();
    $categoryModel = new Category();
    
    // Debug database connection
    if (!$articleModel->hasConnection()) {
        error_log("Pas de connexion à la base de données");
        throw new Exception("Échec de la connexion à la base de données");
    }
    
    // Get latest articles
    $latestArticles = $articleModel->getAll(8);
    
    // Get trending articles
    $trendingArticles = $articleModel->getPopular(5);
    
    // Load contact configuration
    $contact = require __DIR__ . '/../../config/contact.php';
    
    // Social media formatting
    $socialColors = [
        'facebook' => '#39569E',
        'twitter' => '#52AAF4', 
        'linkedin' => '#0185AE',
        'instagram' => '#C8359D',
        'youtube' => '#DC472E'
    ];

    $socialLabels = [
        'facebook' => 'Fans',
        'twitter' => 'Followers',
        'linkedin' => 'Connections',
        'instagram' => 'Followers',
        'youtube' => 'Subscribers'
    ];

    // Format social media data
    $formattedSocialLinks = [];
    if (isset($contact['social']) && is_array($contact['social'])) {
        foreach ($contact['social'] as $platform => $url) {
            $formattedSocialLinks[$platform] = [
                'url' => $url,
                'color' => $socialColors[$platform] ?? '#666666',
                'followers' => '12,345', // Default follower count
                'label' => $socialLabels[$platform] ?? 'Followers'
            ];
        }
    }
    
    if (empty($latestArticles)) {
        error_log("Aucun article trouvé");
    }
    
} catch (Exception $e) {
    error_log("Erreur de la barre latérale : " . $e->getMessage());
    $latestArticles = [];
    $trendingArticles = [];
    $formattedSocialLinks = [];
}

// Verify data before display
// var_dump($latestArticles); // Debug output
?>
<!-- News With Sidebar Start -->
<div class="container-fluid py-3" dir="<?php echo Translate::getCurrentLang() === 'ar' ? 'rtl' : 'ltr'; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 pt-3 pt-lg-0">
                <!-- Social Follow Start -->
                <div class="pb-3">
                    <div class="bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('follow_us'); ?></h3>
                    </div>
                    <?php if (!empty($formattedSocialLinks)): ?>
                    <?php foreach ($formattedSocialLinks as $platform => $link): ?>
                    <div class="d-flex mb-3">
                        <a href="<?php echo htmlspecialchars($link['url']); ?>"
                            class="d-block w-50 py-2 px-3 text-white text-decoration-none mr-2"
                            style="background: <?php echo htmlspecialchars($link['color']); ?>;">
                            <small
                                class="fab fa-<?php echo htmlspecialchars($platform); ?> mr-2"></small><small><?php echo htmlspecialchars($link['followers']); ?>
                                <?php echo htmlspecialchars($link['label']); ?></small>
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p><?php echo Translate::get('no_social_links'); ?></p>
                    <?php endif; ?>
                </div>
                <!-- Social Follow End -->

                <!-- Newsletter Start -->
                <div class="pb-3">
                    <div class="bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('newsletter'); ?></h3>
                    </div>
                    <div class="bg-light text-center p-4 mb-3">
                        <p><?php echo Translate::get('newsletter_description'); ?></p>
                        <div class="input-group" style="width: 100%;">
                            <input type="text" class="form-control form-control-lg"
                                placeholder="<?php echo Translate::get('your_email'); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary"><?php echo Translate::get('sign_up'); ?></button>
                            </div>
                        </div>
                        <small><?php echo Translate::get('newsletter_note'); ?></small>
                    </div>
                </div>
                <!-- Newsletter End -->

                <!-- Ads Start -->
                <div class="mb-3 pb-3">
                    <a href=""><img class="img-fluid"
                            src="<?php echo $app['constants']['ASSETS_URL']; ?>/img/news-500x280-4.jpg" alt=""></a>
                </div>
                <!-- Ads End -->

                <!-- Popular News Start -->
                <div class="pb-3">
                    <div class="bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('trending'); ?></h3>
                    </div>
                    <?php if (!empty($trendingArticles)): ?>
                    <?php foreach ($trendingArticles as $article): ?>
                    <div class="d-flex mb-3">
                        <img src="<?php echo htmlspecialchars($article['image'] ?? $app['constants']['ASSETS_URL'] . '/img/default.jpg'); ?>"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
                            style="height: 100px;">
                            <div class="mb-1" style="font-size: 13px;">
                                <a
                                    href="/category/<?php echo htmlspecialchars($article['category_id']); ?>"><?php echo htmlspecialchars($article['category']); ?></a>
                                <span class="px-1">/</span>
                                <span><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <div
                                <?php echo isset($article['language']) && $article['language'] === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
                                <p class="m-0">
                                    <?php 
                                    $content = html_entity_decode(
                                    html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                                    ENT_QUOTES | ENT_HTML5, 
                                    'UTF-8'
                                );
                                $content = strip_tags($content);
                                echo htmlspecialchars(substr($content, 0, 100)) . '...'; 
                                ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p><?php echo Translate::get('no_trending_articles'); ?></p>
                    <?php endif; ?>
                </div>
                <!-- Popular News End -->

                <!-- Tags Start -->
                <div class="pb-3">
                    <div class="bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('tags'); ?></h3>
                    </div>
                    <div class="d-flex flex-wrap m-n1">
                        <?php
                    $categories = $categoryModel->getAll();
                    if (!empty($categories)):
                        foreach ($categories as $category): ?>
                        <a href="/category/<?php echo urlencode($category['slug']); ?>"
                            class="btn btn-sm btn-outline-secondary m-1"><?php echo htmlspecialchars($category['name']); ?></a>
                        <?php endforeach;
                    else: ?>
                        <p><?php echo Translate::get('no_tags'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Tags End -->
            </div>
            
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
                        <div <?php echo $article['language'] === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
                            <h3 class="mb-3">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h3>
                            <div class="article-content">
                                <?php 
                                    $content = html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                    $content = trim($content);
                                    echo $content; 
                                    ?>
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
                            <textarea id="message" name="content" cols="30" rows="5" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($recaptchaKey); ?>">
                            </div>
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

<!-- Add CSS for article content -->
<style>
.article-content {
    width: 100%;
    overflow-x: hidden;
}

.article-content figure.image {
    margin: 1em 0;
    width: 100% !important;
    text-align: center;
}

.article-content figure.image img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
    border-radius: 4px;
}

/* Optional: Add shadow and hover effect */
.article-content figure.image img:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* Handle image captions if present */
.article-content figure.image figcaption {
    font-size: 0.9em;
    color: #666;
    margin-top: 0.5em;
}
</style>
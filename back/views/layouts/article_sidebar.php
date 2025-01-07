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

<div class="container" dir="<?php echo Translate::getCurrentLang() === 'ar' ? 'rtl' : 'ltr'; ?>">
    <div class="row">
        <div class="col-lg-8" style="left: 33.7%;">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('popular'); ?></h3>
                        <a class="text-secondary font-weight-medium text-decoration-none"
                            href="/articles"><?php echo Translate::get('view_all'); ?></a>
                    </div>
                </div>
                <?php if (!empty($trendingArticles)): ?>
                <?php foreach ($trendingArticles as $article): ?>
                <div class="col-lg-6">
                    <div class="position-relative mb-3">
                        <img class="img-fluid w-100"
                            src="<?php echo htmlspecialchars($article['image'] ?? $app['constants']['ASSETS_URL'] . '/img/default.jpg'); ?>"
                            style="object-fit: cover;">
                        <div class="overlay position-relative bg-light">
                            <div class="mb-2" style="font-size: 14px;">
                                <a
                                    href="/category/<?php echo htmlspecialchars($article['category_id']); ?>"><?php echo htmlspecialchars($article['category']); ?></a>
                                <span class="px-1">/</span>
                                <span><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <div
                                <?php echo isset($article['language']) && $article['language'] === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
                                <a class="h6 m-0" href="/article/<?php echo urlencode($article['title']); ?>">
                                    <?php 
    $title = html_entity_decode(
        html_entity_decode($article['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ENT_QUOTES | ENT_HTML5, 
        'UTF-8'
    );
    echo htmlspecialchars($title); 
    ?>
                                </a>
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
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p><?php echo Translate::get('no_popular_articles'); ?></p>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('latest'); ?></h3>
                        <a class="text-secondary font-weight-medium text-decoration-none"
                            href="/articles"><?php echo Translate::get('view_all'); ?></a>
                    </div>
                </div>
                <?php if (!empty($latestArticles)): ?>
                <?php foreach ($latestArticles as $article): ?>
                <div class="col-lg-6">
                    <div class="position-relative mb-3">
                        <img class="img-fluid w-100"
                            src="<?php echo htmlspecialchars($article['image'] ?? $app['constants']['ASSETS_URL'] . '/img/default.jpg'); ?>"
                            style="object-fit: cover;">
                        <div class="overlay position-relative bg-light">
                            <div class="mb-2" style="font-size: 14px;">
                                <a
                                    href="/category/<?php echo htmlspecialchars($article['category_id']); ?>"><?php echo htmlspecialchars($article['category']); ?></a>
                                <span class="px-1">/</span>
                                <span><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <div
                                <?php echo isset($article['language']) && $article['language'] === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
                                <a class="h4" href="/article/<?php echo urlencode($article['title']); ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
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
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p><?php echo Translate::get('no_latest_articles'); ?></p>
                <?php endif; ?>
            </div>
        </div>

    </div>
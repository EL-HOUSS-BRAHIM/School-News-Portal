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

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('popular'); ?></h3>
                        <a class="text-secondary font-weight-medium text-decoration-none" href="/articles"><?php echo Translate::get('view_all'); ?></a>
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

            <div class="mb-3 pb-3">
                <a href=""><img class="img-fluid w-100"
                        src="<?php echo $app['constants']['ASSETS_URL']; ?>/img/ads-700x70.jpg" alt=""></a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0"><?php echo Translate::get('latest'); ?></h3>
                        <a class="text-secondary font-weight-medium text-decoration-none" href="/articles"><?php echo Translate::get('view_all'); ?></a>
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
                        <input type="text" class="form-control form-control-lg" placeholder="<?php echo Translate::get('your_email'); ?>">
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
                    <div class="w-100 d-flex flex-column justify-content-center bg-light px-3" style="height: 100px;">
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
    </div>
</div>
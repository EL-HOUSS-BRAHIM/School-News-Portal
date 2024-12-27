<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../config/app.php';

try {
    $articleModel = new Article();
    $categoryModel = new Category();
    
    // Debug database connection
    if (!$articleModel->hasConnection()) {
        error_log("No database connection");
        throw new Exception("Database connection failed");
    }
    
    // Get latest articles
    $latestArticles = $articleModel->getAll(8);
    
    // Get trending articles
    $trendingArticles = $articleModel->getPopular(5);

    // Get categories for tags
    $categories = $categoryModel->getAll();
    
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
        'linkedin' => 'Connects',
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
    
} catch (Exception $e) {
    error_log("Sidebar Error: " . $e->getMessage());
    $latestArticles = [];
    $trendingArticles = [];
    $categories = [];
    $formattedSocialLinks = [];
}
?>

<div class="col-lg-4">
    <!-- Social Follow Start -->
    <div class="mb-3">
        <div class="section-title mb-0">
            <h4 class="m-0 text-uppercase font-weight-bold">Follow Us</h4>
        </div>
        <div class="bg-white border border-top-0 p-3">
            <?php if (!empty($formattedSocialLinks)): ?>
                <?php foreach($formattedSocialLinks as $platform => $data): ?>
                    <a href="<?php echo htmlspecialchars($data['url']); ?>" 
                       target="_blank"
                       class="d-block w-100 text-white text-decoration-none <?php echo ($platform !== array_key_last($formattedSocialLinks)) ? 'mb-3' : ''; ?>"
                       style="background: <?php echo htmlspecialchars($data['color']); ?>">
                        <i class="fab fa-<?php echo htmlspecialchars($platform); ?> text-center py-4 mr-3" 
                           style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                        <span class="font-weight-medium">
                            <?php echo $data['followers']; ?> 
                            <?php echo htmlspecialchars($data['label']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">No social media links available</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Social Follow End -->

    <!-- Ads Start -->
    <?php if (isset($app['ads']['sidebar'])): ?>
    <div class="mb-3">
        <div class="section-title mb-0">
            <h4 class="m-0 text-uppercase font-weight-bold">Advertisement</h4>
        </div>
        <div class="bg-white text-center border border-top-0 p-3">
            <a href="<?php echo htmlspecialchars($app['ads']['sidebar']['url']); ?>">
                <img class="img-fluid" 
                     src="<?php echo htmlspecialchars($app['ads']['sidebar']['image']); ?>" 
                     alt="Advertisement">
            </a>
        </div>
    </div>
    <?php endif; ?>
    <!-- Ads End -->

    <!-- Trending News Start -->
    <div class="mb-3">
        <div class="section-title mb-0">
            <h4 class="m-0 text-uppercase font-weight-bold">Trending News</h4>
        </div>
        <div class="bg-white border border-top-0 p-3">
            <?php foreach($trendingArticles as $article): ?>
            <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                <img class="img-fluid" 
                     src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                     style="width: 110px; height: 110px; object-fit: cover;" 
                     alt="<?php echo htmlspecialchars($article['title']); ?>">
                <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center border border-left-0">
                    <div class="mb-2">
                        <?php if(isset($article['category'])): ?>
                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" 
                           href="/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                           <?php echo htmlspecialchars($article['category']); ?>
                        </a>
                        <?php endif; ?>
                        <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                    </div>
                    <a class="h6 m-0 text-secondary text-uppercase font-weight-bold" 
                       href="/article/<?php echo htmlspecialchars($article['id']); ?>">
                       <?php echo htmlspecialchars(substr($article['title'], 0, 50)) . '...'; ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Trending News End -->

    <!-- Newsletter Start -->
    <?php require_once 'newsletter.php'; ?>
    <!-- Newsletter End -->

    <!-- Tags Start -->
    <div class="mb-3">
        <div class="section-title mb-0">
            <h4 class="m-0 text-uppercase font-weight-bold">Categories</h4>
        </div>
        <div class="bg-white border border-top-0 p-3">
            <div class="d-flex flex-wrap m-n1">
                <?php foreach($categories as $category): ?>
                <a href="/category/<?php echo htmlspecialchars($category['id']); ?>" 
                   class="btn btn-sm btn-outline-secondary m-1">
                   <?php echo htmlspecialchars($category['name']); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Tags End -->
</div>
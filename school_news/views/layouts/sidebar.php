<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../config/app.php';

try {
    $articleModel = new Article();
    
    // Debug database connection
    if (!$articleModel->hasConnection()) {
        error_log("No database connection");
        throw new Exception("Database connection failed");
    }
    
    // Get latest articles
    $latestArticles = $articleModel->getAll(8);
    
    // Get trending articles
    $trendingArticles = $articleModel->getPopular(5);
    
    if (empty($latestArticles)) {
        error_log("No articles found");
    }
    
} catch (Exception $e) {
    error_log("Sidebar Error: " . $e->getMessage());
    $latestArticles = [];
    $trendingArticles = [];
}

// Verify data before display
// var_dump($latestArticles); // Debug output
?>

<!-- News With Sidebar Start -->
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <!-- Latest Articles Section -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h4 class="m-0 text-uppercase font-weight-bold">Latest News</h4>
                            <a class="text-secondary font-weight-medium text-decoration-none" href="/articles">View All</a>
                        </div>
                    </div>
                    
                    <?php if (!empty($latestArticles)): ?>
                        <!-- First 4 Latest Articles (Large Format) -->
                        <?php foreach(array_slice($latestArticles, 0, 4) as $article): ?>
                        <div class="col-lg-6">
                            <div class="position-relative mb-3">
                                <img class="img-fluid w-100" 
                                     src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                                     style="object-fit: cover; height: 300px;">
                                <div class="bg-white border border-top-0 p-4">
                                    <div class="mb-2">
                                        <?php if(isset($article['category'])): ?>
                                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" 
                                           href="/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                           <?php echo htmlspecialchars($article['category']); ?>
                                        </a>
                                        <?php endif; ?>
                                        <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                                    </div>
                                    <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold" 
                                       href="/article/<?php echo htmlspecialchars($article['id']); ?>">
                                       <?php echo htmlspecialchars($article['title']); ?>
                                    </a>
                                    <p class="m-0">
                                        <?php echo htmlspecialchars(substr(strip_tags($article['content']), 0, 100)) . '...'; ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                                    <div class="d-flex align-items-center">
                                        <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <small class="ml-3"><i class="far fa-eye mr-2"></i><?php echo $article['views'] ?? 0; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!-- Next 4 Latest Articles (Compact Format) -->
                        <?php foreach(array_slice($latestArticles, 4, 4) as $article): ?>
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                                <img class="img-fluid" 
                                     src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                                     style="width: 110px; height: 110px; object-fit: cover;">
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
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-center">No articles found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="col-lg-4">
                <!-- Social Follow Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Follow Us</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        <a href class="d-block w-100 text-white text-decoration-none mb-3" style="background: #39569E;">
                            <i class="fab fa-facebook-f text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Fans</span>
                        </a>
                        <a href class="d-block w-100 text-white text-decoration-none mb-3" style="background: #52AAF4;">
                            <i class="fab fa-twitter text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Followers</span>
                        </a>
                        <a href class="d-block w-100 text-white text-decoration-none mb-3" style="background: #0185AE;">
                            <i class="fab fa-linkedin-in text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Connects</span>
                        </a>
                        <a href class="d-block w-100 text-white text-decoration-none mb-3" style="background: #C8359D;">
                            <i class="fab fa-instagram text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Followers</span>
                        </a>
                        <a href class="d-block w-100 text-white text-decoration-none mb-3" style="background: #DC472E;">
                            <i class="fab fa-youtube text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Subscribers</span>
                        </a>
                        <a href class="d-block w-100 text-white text-decoration-none" style="background: #055570;">
                            <i class="fab fa-vimeo-v text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Followers</span>
                        </a>
                    </div>
                </div>
                <!-- Social Follow End -->

                <!-- Ads Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Advertisement</h4>
                    </div>
                    <div class="bg-white text-center border border-top-0 p-3">
                        <a href><img class="img-fluid" src="img/news-800x500-2.jpg" alt></a>
                    </div>
                </div>
                <!-- Ads End -->

                <!-- Trending News Section -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Trending News</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        <?php foreach($trendingArticles as $article): ?>
                        <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                            <img class="img-fluid" 
                                 src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                                 style="width: 110px; height: 110px; object-fit: cover;">
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

                <?php require_once 'newsletter.php'; ?>
            </div>
        </div>
    </div>
</div>
<!-- News With Sidebar End -->
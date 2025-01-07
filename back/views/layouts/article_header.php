<?php
if (!function_exists('basename') || !function_exists('urlencode')) {
    die('Required PHP extensions are not loaded. Please enable them in php.ini');
}

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../core/Helpers.php';

// Initialize Translate
Translate::init();

try {
    // Get categories for navigation
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    
    // Get app configuration
    $app = require __DIR__ . '/../../config/app.php';
    
    // Get current page for active state
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');

    // Get trending articles (change to getPopular if getTrending does not exist)
    $articleModel = new Article();
    $trendingArticles = $articleModel->getPopular(5); // Assuming getPopular method exists
} catch (Exception $e) {
    error_log("Header Error: " . $e->getMessage());
    $categories = [];
    $trendingArticles = [];
    $app = ['app_name' => 'College Dar Bouazza News'];
}
?>
<!DOCTYPE html>
<html >

<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($app['app_name']); ?> - <?php echo htmlspecialchars($currentPage); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($app['meta_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($app['meta_keywords']); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="google-news-tags" content="education, morocco, casablanca">
    <meta property="og:title" content="<?php echo htmlspecialchars($app['app_name']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($app['meta_description']); ?>">
    <meta property="og:image" content="<?php echo $app['constants']['ASSETS_URL']; ?>/img/logo.png">
    <meta property="og:url" content="<?php echo $app['constants']['BASE_URL']; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?php echo $app['constants']['BASE_URL'] . $_SERVER['REQUEST_URI']; ?>">
    <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/owlcarousel/assets/owl.carousel.min.css"
        rel="stylesheet">
    <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/css/style.css" rel="stylesheet">
    <link rel="alternate" href="https://bross-news-website.infinityfreeapp.com/" hreflang="fr" />
    <link rel="alternate" href="https://bross-news-website.infinityfreeapp.com/en" hreflang="en" />

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXXX-X"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-XXXXXXXXX-X');
    </script>
    <!-- End Google Analytics -->
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center bg-light px-lg-5">
            <div class="col-12 col-md-8">
                <div class="d-flex justify-content-between">
                    <div class="bg-primary text-white text-center py-2" style="width: 100px;"><?php echo Translate::get('trending'); ?></div>
                    <div class="owl-carousel owl-carousel-1 tranding-carousel position-relative d-inline-flex align-items-center ml-3"
                        style="width: calc(100% - 100px); padding-left: 90px;">
                        <?php if (!empty($trendingArticles)): ?>
                        <?php foreach ($trendingArticles as $article): ?>
                        <div class="text-truncate"><a class="text-secondary"
                                href="/article/<?php echo urlencode($article['title']); ?>">
                                <?php 
                                $title = html_entity_decode(
                        html_entity_decode($article['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        ENT_QUOTES | ENT_HTML5, 
                    'UTF-8'
    );
    echo htmlspecialchars($title); 
    ?>
                            </a></div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-truncate"><a class="text-secondary" href="#"><?php echo Translate::get('no_trending_articles'); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-right d-none d-md-block">
                <?php echo date('l, F d, Y'); ?>
            </div>
        </div>
        <div class="row align-items-center py-2 px-lg-5">
            <div class="col-lg-4">
                <a href="/" class="navbar-brand d-none d-lg-block">
                    <h1 class="m-0 display-5 text-uppercase"><span class="text-primary"
                            style="white-space: wrap;"><?php echo htmlspecialchars($app['app_name'] ?? ''); ?></span>
                    </h1>
                </a>
            </div>

        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid p-0 mb-3">
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-2 py-lg-0 px-lg-5">
            <a href="/" class="navbar-brand d-block d-lg-none">
                <h1 class="m-0 display-5 text-uppercase"><span class="text-primary"
                        style="white-space: wrap;"><?php echo htmlspecialchars($app['app_name'] ?? ''); ?></span></h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-0 px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="/"
                        class="nav-item nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>"><?php echo Translate::get('home'); ?></a>
                    <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                    <a href="/category/<?php echo urlencode($category['slug']); ?>" class="nav-item nav-link">
                        <?php 
                                $name = html_entity_decode(
                                    html_entity_decode($category['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                                    ENT_QUOTES | ENT_HTML5, 
                                    'UTF-8'
                                );
                                echo htmlspecialchars($name); 
                                ?>
                    </a>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <a href="#" class="nav-item nav-link"><?php echo Translate::get('no_categories'); ?></a>
                    <?php endif; ?>
                    <a href="/contact" class="nav-item nav-link"><?php echo Translate::get('contact'); ?></a>
                </div>
                <div class="input-group ml-auto" style="width: 100%; max-width: 300px;">
                    <input type="text" class="form-control" placeholder="<?php echo Translate::get('search'); ?>">
                    <div class="input-group-append">
                        <button class="input-group-text text-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
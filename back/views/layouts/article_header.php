<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/Category.php';

try {
    // Get categories for navigation
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    
    // Get app configuration
    $app = require __DIR__ . '/../../config/app.php';
    
    // Get current page for active state
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
} catch (Exception $e) {
    error_log("Header Error: " . $e->getMessage());
    $categories = [];
    $app = ['app_name' => 'School News Portal'];
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title><?php echo htmlspecialchars($app['app_name']); ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="<?php echo htmlspecialchars($app['meta_keywords'] ?? ''); ?>" name="keywords">
        <meta content="<?php echo htmlspecialchars($app['meta_description'] ?? ''); ?>" name="description">

        <!-- Favicon -->
        <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Font Awesome -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css"
            rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/owlcarousel/assets/owl.carousel.min.css"
            rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/css/style.css" rel="stylesheet">
    </head>

    <body>
        <!-- Topbar Start -->
        <div class="container-fluid d-none d-lg-block">

            <div class="row align-items-center bg-white py-3 px-lg-5">
                <div class="col-lg-4">
                    <a href="<?php echo $app['constants']['BASE_URL']; ?>"
                        class="navbar-brand p-0 d-none d-lg-block">
                        <h1
                            class="m-0 display-4 text-uppercase text-primary"><?php echo htmlspecialchars($app['site_name'] ?? 'School'); ?><span
                                class="text-secondary font-weight-normal"><?php echo htmlspecialchars($app['site_subtitle'] ?? 'News'); ?></span></h1>
                    </a>
                </div>

            </div>
        </div>
        <!-- Topbar End -->

        <!-- Navbar Start -->
        <div class="container-fluid p-0">
            <nav
                class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
                <a href="<?php echo $app['constants']['BASE_URL']; ?>" class="navbar-brand d-block d-lg-none">
                    <h1
                        class="m-0 display-4 text-uppercase text-primary"><?php echo htmlspecialchars($app['site_name_short'] ?? 'School'); ?><span
                            class="text-white font-weight-normal">News</span></h1>
                </a>
                <button type="button" class="navbar-toggler"
                    data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse justify-content-between px-0 px-lg-3"
                    id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="<?php echo $app['constants']['BASE_URL']; ?>"
                            class="nav-item nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>">Home</a>
                        
                        <?php if(!empty($categories)): ?>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Categories</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <?php foreach($categories as $category): ?>
                                    <a href="<?php echo $app['constants']['BASE_URL']; ?>/category/<?php echo htmlspecialchars($category['id']); ?>" 
                                       class="dropdown-item"><?php echo htmlspecialchars($category['name']); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo $app['constants']['BASE_URL']; ?>/contact" 
                           class="nav-item nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">Contact</a>
                    </div>
                    <div class="input-group ml-auto d-none d-lg-flex"
                        style="width: 100%; max-width: 300px;">
                        <form action="<?php echo $app['constants']['BASE_URL']; ?>/search" method="GET" class="w-100">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control border-0" placeholder="Search news...">
                                <div class="input-group-append">
                                    <button class="input-group-text text-dark border-0 px-3" 
                                            style="background-color: #2558d8;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->
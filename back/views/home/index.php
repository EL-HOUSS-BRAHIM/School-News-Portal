<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Fetch data from the database
$articleModel = new Article();
$categoryModel = new Category();

$featuredArticles = $articleModel->getFeatured(5);
$categories = $categoryModel->getAll();
?>

<!-- Featured News Slider Start -->
<div class="container-fluid py-3">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
            <h3 class="m-0">Featured</h3>
            <a class="text-secondary font-weight-medium text-decoration-none" href="/articles">View All</a>
        </div>
        <?php if (!empty($featuredArticles)): ?>
            <div class="owl-carousel owl-carousel-2 carousel-item-4 position-relative">
                <?php foreach ($featuredArticles as $article): ?>
                    <div class="position-relative overflow-hidden" style="height: 300px;">
                        <img class="img-fluid w-100 h-100"
                             src="<?php echo htmlspecialchars($article['image'] ?? $app['constants']['ASSETS_URL'] . '/img/default.jpg'); ?>"
                             style="object-fit: cover;">
                        <div class="overlay">
                            <div class="mb-1" style="font-size: 13px;">
                                <?php
                                $categoryName = $article['category_name'] ?? 'Uncategorized';
                                ?>
                                <a class="text-white" href="/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                    <?php echo htmlspecialchars($categoryName); ?>
                                </a>
                                <span class="px-1 text-white">/</span>
                                <a class="text-white" href="/article/<?php echo urlencode($article['title']); ?>">
                                    <?php echo date('F d, Y', strtotime($article['created_at'] ?? 'now')); ?>
                                </a>
                            </div>
                            <a class="h4 m-0 text-white" href="/article/<?php echo urlencode($article['title']); ?>">
                                <?php
                                // Use language-based fallback or default to 'Untitled'
                                echo htmlspecialchars($article['title'] ?? 'Untitled');
                                ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No featured articles available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Featured News Slider End -->

<!-- Category News Slider Start -->
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-lg-6 py-3">
                        <div class="bg-light py-2 px-4 mb-3">
                            <h3 class="m-0"><?php echo htmlspecialchars($category['name']); ?></h3>
                        </div>
                        <?php
                        $categoryArticles = $articleModel->getByCategory($category['id']);
                        error_log("Category {$category['name']}: " . count($categoryArticles) . " articles found");
                        if (!empty($categoryArticles)): ?>
                            <div class="owl-carousel owl-carousel-3 carousel-item-2 position-relative">
                                <?php foreach ($categoryArticles as $article): ?>
                                    <div class="position-relative">
                                        <img class="img-fluid w-100"
                                             src="<?php echo htmlspecialchars($article['image'] ?? $app['constants']['ASSETS_URL'] . '/img/default.jpg'); ?>"
                                             style="object-fit: cover;">
                                        <div class="overlay position-relative bg-light">
                                            <div class="mb-2" style="font-size: 13px;">
                                                <a href="/category/<?php echo htmlspecialchars($category['id']); ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </a>
                                                <span class="px-1">/</span>
                                                <span><?php echo date('F d, Y', strtotime($article['created_at'] ?? 'now')); ?></span>
                                            </div>
                                            <a class="h4 m-0" href="/article/<?php echo urlencode($article['title']); ?>">
                                                <?php echo htmlspecialchars($article['title'] ?? 'Untitled'); ?>
                                            </a>
                                            <p class="m-0" dir="<?php echo $article['language'] === 'ar' ? 'rtl' : 'ltr'; ?>">
                                                <?php 
                                                // Double decode HTML entities since content is double encoded
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
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>No articles available in <?php echo htmlspecialchars($category['name']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No categories available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Category News Slider End -->

<!-- News With Sidebar Start -->
<div class="container-fluid py-3">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
</div>
<!-- News With Sidebar End -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
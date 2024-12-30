<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Main News Slider Start -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 px-0">
            <div class="owl-carousel main-carousel position-relative">
                <?php if (!empty($mainSliderArticles)): ?>
                    <?php foreach($mainSliderArticles as $article): ?>
                    <div class="position-relative overflow-hidden" style="height: 500px;">
                        <img class="img-fluid h-100" 
                             src="<?php echo htmlspecialchars($article['image'] ?? '/assets/assets/img/default.jpg'); ?>" 
                             style="object-fit: cover;">
                        <div class="overlay">
                            <div class="mb-2">
                                <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" 
                                   href="<?php echo $app['constants']['BASE_URL']; ?>/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                    <?php echo htmlspecialchars($article['category'] ?? 'Non classé'); ?>
                                </a>
                                <span class="text-white">
                                    <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                                </span>
                            </div>
                            <a class="h2 m-0 text-white text-uppercase font-weight-bold" 
                               href="<?php echo $app['constants']['BASE_URL']; ?>/article/<?php echo urlencode($article['title']); ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-5 px-0">
            <div class="row mx-0">
                <?php if (!empty($topArticles)): ?>
                    <?php foreach(array_slice($topArticles, 0, 4) as $article): ?>
                    <div class="col-md-6 px-0">
                        <div class="position-relative overflow-hidden" style="height: 250px;">
                            <img class="img-fluid w-100 h-100" 
                                 src="<?php echo htmlspecialchars($article['image'] ?? '/assets/img/default.jpg'); ?>" 
                                 style="object-fit: cover;">
                            <div class="overlay">
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" 
                                       href="<?php echo $app['constants']['BASE_URL']; ?>/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                        <?php echo htmlspecialchars($article['category'] ?? 'Non classé'); ?>
                                    </a>
                                    <span class="text-white">
                                        <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                                    </span>
                                </div>
                                <a class="h6 m-0 text-white text-uppercase font-weight-semi-bold" 
                                   href="<?php echo $app['constants']['BASE_URL']; ?>/article/<?php echo urlencode($article['title']); ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Main News Slider End -->

<!-- Breaking News Start -->
<div class="container-fluid bg-dark py-3 mb-3">
    <div class="container">
        <div class="row align-items-center bg-dark">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div class="bg-primary text-dark text-center font-weight-medium py-2" 
                         style="width: 170px;">Dernières Nouvelles</div>
                    <div class="owl-carousel tranding-carousel position-relative d-inline-flex align-items-center ml-3"
                         style="width: calc(100% - 170px); padding-right: 90px;">
                        <?php if (!empty($breakingNews)): ?>
                            <?php foreach($breakingNews as $article): ?>
                            <div class="text-truncate">
                                <a class="text-white text-uppercase font-weight-semi-bold" 
                                   href="<?php echo $app['constants']['BASE_URL']; ?>/article/<?php echo urlencode($article['title']); ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breaking News End -->

<!-- Featured News Slider Start -->
<div class="container-fluid pt-5 mb-3">
    <div class="container">
        <div class="section-title">
            <h4 class="m-0 text-uppercase font-weight-bold">Nouvelles en Vedette</h4>
        </div>
        <div class="owl-carousel news-carousel carousel-item-4 position-relative">
            <?php if (!empty($featuredArticles)): ?>
                <?php foreach($featuredArticles as $article): ?>
                <div class="position-relative overflow-hidden" style="height: 300px;">
                    <img class="img-fluid h-100" 
                         src="<?php echo htmlspecialchars($article['image'] ?? '/assets/assets/img/default.jpg'); ?>" 
                         style="object-fit: cover;">
                    <div class="overlay">
                        <div class="mb-2">
                            <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" 
                               href="<?php echo $app['constants']['BASE_URL']; ?>/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                <?php echo htmlspecialchars($article['category'] ?? 'Non classé'); ?>
                            </a>
                            <span class="text-white">
                                <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                            </span>
                        </div>
                        <a class="h6 m-0 text-white text-uppercase font-weight-semi-bold" 
                           href="<?php echo $app['constants']['BASE_URL']; ?>/article/<?php echo urlencode($article['title']); ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Featured News Slider End -->

<?php 
// Show error message if exists
if (isset($error)): ?>
<div class="container">
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php

require_once __DIR__ . '/../layouts/article_header.php';
?>

<!-- Breaking News Start -->
<div class="container-fluid mt-5 mb-3 pt-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div class="section-title border-right-0 mb-0" style="width: 180px;">
                        <h4 class="m-0 text-uppercase font-weight-bold">Breaking News</h4>
                    </div>
                    <div class="owl-carousel tranding-carousel position-relative d-inline-flex align-items-center bg-white border border-left-0" 
                         style="width: calc(100% - 180px); padding-right: 100px;">
                        <?php if (!empty($breakingNews)): ?>
                            <?php foreach($breakingNews as $news): ?>
                                <div class="text-truncate">
                                    <a class="text-secondary text-uppercase font-weight-semi-bold" 
                                       href="/article/<?php echo urlencode($news['title']); ?>">
                                        <?php echo htmlspecialchars($news['title']); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-truncate">
                                <span class="text-secondary">No breaking news available</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breaking News End -->

<!-- News With Sidebar Start -->
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (isset($article) && $article): ?>
                    <!-- News Detail Start -->
                    <div class="position-relative mb-3">
                        <img class="img-fluid w-100" 
                             src="<?php echo htmlspecialchars($article['image'] ?? '/img/default.jpg'); ?>" 
                             style="object-fit: cover;">
                        <div class="bg-white border border-top-0 p-4">
                            <div class="mb-3">
                                <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" 
                                   href="/category/<?php echo htmlspecialchars($article['category_id']); ?>">
                                    <?php echo htmlspecialchars($article['category']); ?>
                                </a>
                                <span class="text-body">
                                    <?php 
                                    if (!empty($article['created_at'])) {
                                        echo date('M d, Y', strtotime($article['created_at']));
                                    } else {
                                        echo 'N/A';
                                    } 
                                    ?>
                                </span>
                            </div>
                            <h1 class="mb-3 text-secondary text-uppercase font-weight-bold">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h1>
                            <div class="article-content">
                                <?php echo html_entity_decode($article['content']); ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                            <div class="d-flex align-items-center">
                                <span>By <?php echo htmlspecialchars($article['author'] ?? 'Anonymous'); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <i class="far fa-eye mr-2"></i>
                                    <?php echo (int)($article['views'] ?? 0); ?>
                                </span>
                                <span class="ml-3">
                                    <i class="far fa-comment mr-2"></i>
                                    <?php echo count($comments ?? []); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- News Detail End -->

                    <!-- Comment List Start -->
                    <div class="mb-3">
                        <div class="section-title mb-0">
                            <h4 class="m-0 text-uppercase font-weight-bold"><?php echo count($comments); ?> Comments</h4>
                        </div>
                        <div class="bg-white border border-top-0 p-4">
                            <?php foreach($comments as $comment): ?>
                            <div class="media mb-4">
                                <img src="/img/user.jpg" alt="User" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                <div class="media-body">
                                    <h6>
                                        <span class="text-secondary font-weight-bold">
                                            <?php echo htmlspecialchars($comment['username']); ?>
                                        </span> 
                                        <small><i><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></i></small>
                                    </h6>
                                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Comment List End -->

                    <!-- Comment Form Start -->
                    <div class="mb-3">
                        <div class="section-title mb-0">
                            <h4 class="m-0 text-uppercase font-weight-bold">Leave a comment</h4>
                        </div>
                        <div class="bg-white border border-top-0 p-4">
                            <form action="/comment/add" method="POST">
                                <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">
                                <div class="form-group">
                                    <label for="message">Message *</label>
                                    <textarea id="message" name="content" cols="30" rows="5" class="form-control" required></textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <button type="submit" class="btn btn-primary font-weight-semi-bold py-2 px-3">
                                            Leave a comment
                                        </button>
                                    <?php else: ?>
                                        <a href="/login" class="btn btn-primary font-weight-semi-bold py-2 px-3">
                                            Login to comment
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Comment Form End -->
                <?php else: ?>
                    <div class="alert alert-warning">Article not found</div>
                <?php endif; ?>
            </div>

            <?php include __DIR__ . '/../layouts/article_sidebar.php'; ?>
        </div>
    </div>
</div>
<!-- News With Sidebar End -->

<?php include __DIR__ . '/../layouts/article_footer.php'; ?>
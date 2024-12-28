<?php
require_once __DIR__ . '/../../models/Article.php';
$contact = require __DIR__ . '/../../config/contact.php';
$popularArticles = (new Article())->getPopular(3);
$app = require __DIR__ . '/../../config/app.php';


try {
    // Verify $contact is an array and has required keys
    if (!is_array($contact)) {
        throw new Exception('Contact configuration must be an array');
    }
    
} catch (Exception $e) {
    error_log("Footer Error: " . $e->getMessage());
    $contact = [
        'address' => 'Address not available',
        'phone' => 'Phone not available', 
        'email' => 'Email not available',
        'social' => []
    ];
    $popularArticles = [];
    $app = ['app_name' => 'School News Portal'];
}

// Set default values if keys don't exist
$contact = array_merge([
    'address' => 'Address not available',
    'phone' => 'Phone not available',
    'email' => 'Email not available', 
    'social' => []
], $contact ?? []);

// Ensure social is an array
if (!isset($contact['social']) || !is_array($contact['social'])) {
    $contact['social'] = [];
}
?>

<!-- Footer Start -->
<div class="container-fluid bg-dark pt-5 px-sm-3 px-md-5 mt-5">
    <div class="row py-4">
        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="mb-4 text-white text-uppercase font-weight-bold">Get In Touch</h5>
            <p class="font-weight-medium text-white">
                <i class="fa fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($contact['address']); ?>
            </p>
            <p class="font-weight-medium text-white">
                <i class="fa fa-phone-alt mr-2"></i><?php echo htmlspecialchars($contact['phone']); ?>
            </p>
            <p class="font-weight-medium text-white">
                <i class="fa fa-envelope mr-2"></i><?php echo htmlspecialchars($contact['email']); ?>
            </p>
            
            <h6 class="mt-4 mb-3 text-white text-uppercase font-weight-bold">Follow Us</h6>
            <div class="d-flex justify-content-start">
                <?php if (!empty($contact['social'])): ?>
                    <?php foreach($contact['social'] as $platform => $url): ?>
                        <a class="btn btn-lg btn-secondary btn-lg-square mr-2" 
                           href="<?php echo htmlspecialchars($url); ?>"
                           target="_blank">
                            <i class="fab fa-<?php echo htmlspecialchars($platform); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-white">No social media links available</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="mb-4 text-white text-uppercase font-weight-bold">Popular News</h5>
            <?php foreach($popularArticles as $article): ?>
            <div class="mb-3">
                <div class="mb-2">
                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" href="">
                        <?php echo htmlspecialchars($article['category']); ?>
                    </a>
                    <a class="text-body" href="">
                        <small><?php echo date('M d, Y', strtotime($article['created_at'])); ?></small>
                    </a>
                </div>
                <a class="small text-body text-uppercase font-weight-medium" href="">
                    <?php echo htmlspecialchars(substr($article['title'], 0, 50)) . '...'; ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container-fluid py-4 px-sm-3 px-md-5" style="background: #111111;">
    <p class="m-0 text-center">
        © <?php echo date('Y'); ?> 
        <a href="#">
            <?php echo htmlspecialchars($app['app_name'] ?? 'School News Portal'); ?>
        </a>. 
        All Rights Reserved.
    </p>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-square back-to-top">
    <i class="fa fa-arrow-up"></i>
</a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/easing/easing.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/js/main.js"></script>
<script>
$(document).ready(function() {
    $(".main-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        items: 1,
        dots: true,
        loop: true,
        nav : true,
        navText : [
            '<i class="fa fa-angle-left"></i>',
            '<i class="fa fa-angle-right"></i>'
        ]
    });
});
</script>
</body>
</html>
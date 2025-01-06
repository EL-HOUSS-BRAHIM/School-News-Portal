<?php
// Get configurations with error checking
try {
    require_once __DIR__ . '/../../models/Article.php';
    $contact = require __DIR__ . '/../../config/contact.php';
    $popularArticles = (new Article())->getPopular(3);
    $app = require __DIR__ . '/../../config/app.php';
    
    // Verify $contact is an array and has required keys
    if (!is_array($contact)) {
        throw new Exception('La configuration du contact doit être un tableau');
    }
    
} catch (Exception $e) {
    error_log("Erreur du pied de page de l'article : " . $e->getMessage());
    $contact = [
        'address' => Translate::get('address_not_available'),
        'phone' => Translate::get('phone_not_available'), 
        'email' => Translate::get('email_not_available'),
        'social' => []
    ];
    $popularArticles = [];
    $app = ['app_name' => Translate::get('news_portal')];
}

// Set default values if keys don't exist
$contact = array_merge([
    'address' => Translate::get('address_not_available'),
    'phone' => Translate::get('phone_not_available'),
    'email' => Translate::get('email_not_available'), 
    'social' => []
], $contact ?? []);

// Ensure social is an array
if (!isset($contact['social']) || !is_array($contact['social'])) {
    $contact['social'] = [];
}
?>
<!-- Footer Start -->
<div class="container-fluid bg-light pt-5 px-sm-3 px-md-5">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-5">
            <a href="/" class="navbar-brand">
                <h1 class="mb-2 mt-n2 display-5 text-uppercase"><span class="text-primary"><?php echo htmlspecialchars($app['app_name']); ?></span></h1>
            </a>
            <p><?php echo htmlspecialchars($app['description']); ?></p>
            <div class="d-flex justify-content-start mt-4">
                <?php foreach ($contact['social'] as $platform => $url): ?>
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" 
                       style="width: 38px; height: 38px;" 
                       href="<?php echo htmlspecialchars($url); ?>"
                       target="_blank">
                        <i class="fab fa-<?php echo htmlspecialchars($platform); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4"><?php echo Translate::get('categories'); ?></h4>
            <div class="d-flex flex-wrap m-n1">
                <?php foreach ($categories as $category): ?>
                    <a href="/category/<?php echo urlencode($category['slug']); ?>" 
                       class="btn btn-sm btn-outline-secondary m-1">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4"><?php echo Translate::get('tags'); ?></h4>
            <div class="d-flex flex-wrap m-n1">
                <?php foreach ($categories as $category): ?>
                    <a href="/tag/<?php echo urlencode($category['slug']); ?>" 
                       class="btn btn-sm btn-outline-secondary m-1">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4"><?php echo Translate::get('quick_links'); ?></h4>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-secondary mb-2" href="/about"><i class="fa fa-angle-right text-dark mr-2"></i><?php echo Translate::get('about'); ?></a>
                <a class="text-secondary mb-2" href="/advertise"><i class="fa fa-angle-right text-dark mr-2"></i><?php echo Translate::get('advertise'); ?></a>
                <a class="text-secondary mb-2" href="/privacy"><i class="fa fa-angle-right text-dark mr-2"></i><?php echo Translate::get('privacy_policy'); ?></a>
                <a class="text-secondary mb-2" href="/terms"><i class="fa fa-angle-right text-dark mr-2"></i><?php echo Translate::get('terms_of_use'); ?></a>
                <a class="text-secondary" href="/contact"><i class="fa fa-angle-right text-dark mr-2"></i><?php echo Translate::get('contact'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4 px-sm-3 px-md-5" style="background: #111111;">
    <p class="m-0 text-center">
        © <?php echo date('Y'); ?> 
        <a href="/">
            <?php echo htmlspecialchars($app['app_name']); ?>
        </a>. 
        <?php echo Translate::get('all_rights_reserved'); ?>.
        <br>
        <?php echo Translate::get('developed_by'); ?> <a href="https://github.com/EL-HOUSS-BRAHIM/" target="_blank">Brahim Elhouss</a>.
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
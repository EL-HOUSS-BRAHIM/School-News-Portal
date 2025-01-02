<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Category.php';
$contact = require __DIR__ . '/../../config/contact.php';
$popularArticles = (new Article())->getPopular(3);
$app = require __DIR__ . '/../../config/app.php';

try {
    // Get categories for both Categories and Tags sections
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();

    // Verify $contact is an array and has required keys
    if (!is_array($contact)) {
        throw new Exception('La configuration du contact doit être un tableau');
    }
} catch (Exception $e) {
    error_log("Erreur du pied de page : " . $e->getMessage());
    $categories = [];
    $contact = [
        'address' => 'Adresse non disponible',
        'phone' => 'Téléphone non disponible', 
        'email' => 'Email non disponible',
        'social' => []
    ];
    $popularArticles = [];
    $app = ['app_name' => 'Portail des Nouvelles Scolaires'];
}

// Define social media platforms with their icons
$socialPlatforms = [
    'twitter' => 'fab fa-twitter',
    'facebook' => 'fab fa-facebook-f',
    'linkedin' => 'fab fa-linkedin-in',
    'instagram' => 'fab fa-instagram',
    'youtube' => 'fab fa-youtube'
];

// Define quick links
$quickLinks = [
    'about' => ['url' => '/about', 'text' => 'À propos'],
    'advertise' => ['url' => '/advertise', 'text' => 'Publicité'],
    'privacy' => ['url' => '/privacy', 'text' => 'Politique de confidentialité'],
    'terms' => ['url' => '/terms', 'text' => 'Conditions d\'utilisation'],
    'contact' => ['url' => '/contact', 'text' => 'Contact']
];
?>
<!-- Footer Start -->
<div class="container-fluid bg-light pt-5 px-sm-3 px-md-5">
    <div class="row">
        <!-- Brand and Social Media -->
        <div class="col-lg-3 col-md-6 mb-5">
            <a href="/" class="navbar-brand">
                <h1 class="mb-2 mt-n2 display-5 text-uppercase">
                    <span class="text-primary" style="white-space: wrap;"><?php echo htmlspecialchars($app['app_name'] ?? ''); ?></span>
                </h1>
            </a>
            <p><?php echo htmlspecialchars($app['description'] ?? ''); ?></p>
            <div class="d-flex justify-content-start mt-4">
                <?php foreach ($socialPlatforms as $platform => $icon): ?>
                    <?php if (!empty($contact['social'][$platform])): ?>
                        <a class="btn btn-outline-secondary text-center mr-2 px-0" 
                           style="width: 38px; height: 38px;" 
                           href="<?php echo htmlspecialchars($contact['social'][$platform]); ?>"
                           target="_blank">
                            <i class="<?php echo $icon; ?>"></i>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4">Categories</h4>
            <div class="d-flex flex-wrap m-n1">
                <?php foreach ($categories as $category): ?>
                    <a href="/category/<?php echo urlencode($category['slug']); ?>" 
                       class="btn btn-sm btn-outline-secondary m-1">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tags (using categories) -->
        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4">Tags</h4>
            <div class="d-flex flex-wrap m-n1">
                <?php foreach ($categories as $category): ?>
                    <a href="/tag/<?php echo urlencode($category['slug']); ?>" 
                       class="btn btn-sm btn-outline-secondary m-1">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="font-weight-bold mb-4">Liens rapides</h4>
            <div class="d-flex flex-column justify-content-start">
                <?php foreach ($quickLinks as $link): ?>
                    <a class="text-secondary mb-2" href="<?php echo $link['url']; ?>">
                        <i class="fa fa-angle-right text-dark mr-2"></i><?php echo htmlspecialchars($link['text']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Copyright -->
<div class="container-fluid py-4 px-sm-3 px-md-5" style="background: #111111;">
    <p class="m-0 text-center">
        © <?php echo date('Y'); ?> 
        <a href="/">
            <?php echo htmlspecialchars($app['app_name'] ?? 'Portail des Nouvelles Scolaires'); ?>
        </a>. 
        Tous droits réservés.
        <br>
        Développé par <a href="https://github.com/EL-HOUSS-BRAHIM/" target="_blank">Brahim Elhouss</a>.
    </p>
</div>

<!-- Back to Top -->
<a href="#" class="btn btn-dark back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/easing/easing.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="<?php echo $app['constants']['ASSETS_URL']; ?>/js/main.js"></script>
</body>
</html>
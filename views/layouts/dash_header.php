<?php
require_once __DIR__ . '/../../config/app.php';
try {
    
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
    <!--     Fonts and icons     -->
    <link
      href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800"
      rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Files -->
    <link href="<?php echo $app['constants']['ASSETS_URL']; ?>/css/dashboard.css?v=1.1.0"
      rel="stylesheet" />
      <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.css">

  </head>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <h2><?php echo Translate::get('about_us'); ?></h2>
    <p><?php echo Translate::get('about_us_description'); ?></p>
    
    <h3><?php echo Translate::get('our_mission'); ?></h3>
    <p><?php echo Translate::get('our_mission_description'); ?></p>
    
    <h3><?php echo Translate::get('our_team'); ?></h3>
    <p><?php echo Translate::get('our_team_description'); ?></p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
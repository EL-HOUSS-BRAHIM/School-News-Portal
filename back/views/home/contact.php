<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <h2><?php echo Translate::get('contact_us'); ?></h2>
    <p><?php echo Translate::get('contact_us_description'); ?></p>
    
    <form action="/send-contact" method="post">
        <div class="form-group">
            <label for="name"><?php echo Translate::get('name'); ?></label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email"><?php echo Translate::get('email'); ?></label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message"><?php echo Translate::get('message'); ?></label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo Translate::get('send_message'); ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
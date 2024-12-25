<?php
// This file contains the registration page for new users.

include_once '../../includes/header.php';
?>

<div class="container">
    <h2>Register</h2>
    <form action="../../handlers/auth_handler.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php
include_once '../../includes/footer.php';
?>
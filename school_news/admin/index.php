<?php
// admin/index.php

session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../views/auth/login.php');
    exit();
}

// Include necessary files
include '../includes/header.php';
include '../includes/sidebar.php';

// Admin dashboard content
?>

<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin panel. Here you can manage articles, categories, users, and comments.</p>
    <ul>
        <li><a href="articles.php">Manage Articles</a></li>
        <li><a href="categories.php">Manage Categories</a></li>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="comments.php">Manage Comments</a></li>
    </ul>
</div>

<?php
include '../includes/footer.php';
?>
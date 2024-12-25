<?php
// Admin Dashboard Template

// Include necessary files
include_once '../../includes/header.php';
include_once '../../includes/sidebar.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch admin-related data (e.g., total articles, users, comments)
$totalArticles = 100; // Example data
$totalUsers = 50; // Example data
$totalComments = 200; // Example data
?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="stats">
        <div class="stat">
            <h2>Total Articles</h2>
            <p><?php echo $totalArticles; ?></p>
        </div>
        <div class="stat">
            <h2>Total Users</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat">
            <h2>Total Comments</h2>
            <p><?php echo $totalComments; ?></p>
        </div>
    </div>
</div>

<?php
include_once '../../includes/footer.php';
?>
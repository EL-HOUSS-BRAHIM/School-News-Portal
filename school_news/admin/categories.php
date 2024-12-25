<?php
// categories.php - Manages category-related administrative functions

require_once '../config/database.php';
require_once '../core/Category.php';

$category = new Category();

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    if (!empty($name)) {
        $category->create($name);
        header('Location: categories.php');
        exit;
    }
}

// Fetch all categories
$categories = $category->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../public/assets/css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/sidebar.php'; ?>

    <h1>Manage Categories</h1>

    <form action="categories.php" method="POST">
        <input type="text" name="name" placeholder="Category Name" required>
        <button type="submit">Add Category</button>
    </form>

    <h2>Existing Categories</h2>
    <ul>
        <?php foreach ($categories as $cat): ?>
            <li><?php echo htmlspecialchars($cat['name']); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
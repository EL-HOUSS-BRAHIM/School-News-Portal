<?php
// users.php - Manages user-related administrative functions

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../core/User.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create User object
$user = new User($db);

// Handle user-related actions (e.g., list users, add user, delete user)
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'list':
        $users = $user->getAllUsers();
        include '../views/admin/users_list.php'; // Include the view for listing users
        break;

    case 'add':
        // Logic to add a new user
        break;

    case 'delete':
        // Logic to delete a user
        break;

    default:
        // Default action (e.g., show user list)
        $users = $user->getAllUsers();
        include '../views/admin/users_list.php'; // Include the view for listing users
        break;
}
?>
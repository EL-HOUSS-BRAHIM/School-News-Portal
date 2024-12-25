<?php
// comments.php - Manages comment-related administrative functions

require_once '../config/database.php';
require_once '../core/Comment.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create Comment object
$comment = new Comment($db);

// Handle various comment-related actions (e.g., view, delete, approve)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: Handle comment deletion
    if (isset($_POST['delete_comment_id'])) {
        $comment->deleteComment($_POST['delete_comment_id']);
    }
}

// Fetch comments for display
$comments = $comment->getAllComments();

include '../includes/header.php';
?>

<div class="container">
    <h2>Manage Comments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Article ID</th>
                <th>User</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo $c['article_id']; ?></td>
                    <td><?php echo $c['user']; ?></td>
                    <td><?php echo $c['comment']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="delete_comment_id" value="<?php echo $c['id']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
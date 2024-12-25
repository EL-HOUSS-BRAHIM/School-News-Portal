<?php
// comment_handler.php

require_once '../core/Comment.php';

class CommentHandler {
    private $comment;

    public function __construct() {
        $this->comment = new Comment();
    }

    public function addComment($articleId, $userId, $content) {
        // Validate input
        if (empty($content)) {
            return ['status' => 'error', 'message' => 'Comment content cannot be empty.'];
        }

        // Add comment
        $result = $this->comment->create($articleId, $userId, $content);
        return $result ? ['status' => 'success', 'message' => 'Comment added successfully.'] : ['status' => 'error', 'message' => 'Failed to add comment.'];
    }

    public function getComments($articleId) {
        return $this->comment->getByArticleId($articleId);
    }

    public function deleteComment($commentId) {
        return $this->comment->delete($commentId);
    }
}
?>
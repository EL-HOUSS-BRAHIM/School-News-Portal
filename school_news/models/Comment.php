<?php
require_once __DIR__ . '/../core/Model.php';

class Comment extends Model {
    protected $table = 'comments';

    public function getByArticle($articleId) {
        try {
            $sql = "SELECT c.*, u.username 
                    FROM {$this->table} c 
                    LEFT JOIN users u ON c.user_id = u.id 
                    WHERE c.article_id = ? 
                    ORDER BY c.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$articleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting comments: " . $e->getMessage());
            return [];
        }
    }
}
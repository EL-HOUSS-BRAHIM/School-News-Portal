class Comment {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addComment($articleId, $userId, $commentText) {
        $stmt = $this->db->prepare("INSERT INTO comments (article_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $articleId, $userId, $commentText);
        return $stmt->execute();
    }

    public function getComments($articleId) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteComment($commentId) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        return $stmt->execute();
    }
}
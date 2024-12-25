class Article {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function create($title, $content, $category_id) {
        $stmt = $this->db->prepare("INSERT INTO articles (title, content, category_id) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $content, $category_id]);
    }

    public function read($id) {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $title, $content, $category_id) {
        $stmt = $this->db->prepare("UPDATE articles SET title = ?, content = ?, category_id = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $category_id, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM articles");
        return $stmt->fetchAll();
    }
}
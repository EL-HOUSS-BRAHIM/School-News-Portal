<?php
require_once __DIR__ . '/../config/database.php';

class Model
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        try {
            $this->pdo = Database::getInstance()->getConnection();
            if (!$this->pdo) {
                throw new Exception("Failed to get database connection");
            }
        } catch (Exception $e) {
            error_log("Model Connection Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        try {
            // Updated to include all new fields from articles table
            $sql = "SELECT a.*, u.username as author,
                          c.name as category_name
                   FROM {$this->table} a
                   LEFT JOIN users u ON a.user_id = u.id 
                   LEFT JOIN categories c ON a.category_id = c.id
                   WHERE a.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding record: " . $e->getMessage());
            return null;
        }
    }

    public function save($data)
{
    try {
        // Ensure boolean fields are properly converted
        $data['featured'] = isset($data['featured']) ? (int)$data['featured'] : 0;
        $data['breaking'] = isset($data['breaking']) ? (int)$data['breaking'] : 0;

        // Ensure language is set
        if (!isset($data['language']) || empty($data['language'])) {
            $data['language'] = 'fr';
        }

        // Only encode title, leave content as HTML
        if (isset($data['title'])) {
            $data['title'] = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
        }
        // Don't encode content to allow HTML and images
        
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute(array_values($data));

        if (!$result) {
            error_log("Error executing query: " . print_r($stmt->errorInfo(), true));
        }

        return $result;
    } catch (PDOException $e) {
        error_log("Error saving article: " . $e->getMessage());
        throw $e;
    }
}

public function update($id, $data)
{
    try {
        // Do not encode HTML content
        if (isset($data['content'])) {
            // Only sanitize, don't encode HTML
            $data['content'] = filter_var($data['content'], FILTER_UNSAFE_RAW);
        }
        
        // HTML encode only the title and other non-HTML fields
        if (isset($data['title'])) {
            $data['title'] = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
        }

        $set = implode("=?, ", array_keys($data)) . "=?";
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    } catch (PDOException $e) {
        error_log("Error updating record: " . $e->getMessage());
        throw $e;
    }
}

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $result = $stmt->execute([$id]);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting record: " . $e->getMessage());
            throw $e;
        }
    }
}
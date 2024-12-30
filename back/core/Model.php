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
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Close the cursor to free up the connection
        return $result;
    }

public function save($data)
{
    try {
        // Clean content before saving
        if (isset($data['content'])) {
            $data['content'] = strip_tags($data['content'], '<p><br><strong><em><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6>');
        }

        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error saving article: " . $e->getMessage());
        throw $e;
    }
}

    public function update($id, $data)
    {
        $set = implode("=?, ", array_keys($data)) . "=?";
        $values = array_values($data);
        $values[] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        $result = $stmt->execute($values);
        $stmt->closeCursor(); // Close the cursor to free up the connection
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $result = $stmt->execute([$id]);
        $stmt->closeCursor(); // Close the cursor to free up the connection
        return $result;
    }
}
?>
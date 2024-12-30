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
        // Generate UUID if not provided
        if (!isset($data['id'])) {
            $data['id'] = generateUUID();
        }

        if (isset($data['content'])) {
            $data['content'] = html_entity_decode($data['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $data['id']; // Return the UUID instead of lastInsertId()
    } catch (PDOException $e) {
        error_log("Error saving record: " . $e->getMessage());
        throw $e;
    }
}
    
    public function update($id, $data)
    {
        try {
            if (isset($data['content'])) {
                // Decode HTML entities and ensure proper HTML
                $data['content'] = html_entity_decode($data['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
    
            $set = implode("=?, ", array_keys($data)) . "=?";
            $values = array_values($data);
            $values[] = $id;
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
            $result = $stmt->execute($values);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            error_log("Error updating article: " . $e->getMessage());
            throw $e;
        }
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
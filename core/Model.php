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
        // Ensure created_at is set
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        
        $result = $stmt->execute($data);
        $stmt->closeCursor();
        
        return $result;
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
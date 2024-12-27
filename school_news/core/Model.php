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
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($values)");
        $result = $stmt->execute($data);
        $stmt->closeCursor(); // Close the cursor to free up the connection
        return $result;
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
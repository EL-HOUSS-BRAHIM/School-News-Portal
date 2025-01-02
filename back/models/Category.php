<?php

require_once __DIR__ . '/../core/Model.php';

class Category extends Model
{
    protected $table = 'categories';

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    
    public function delete($id)
    {
        try {
            // Start transaction
            $this->pdo->beginTransaction();

            // First update all articles in this category
            $sql = "UPDATE articles 
                   SET status = ?, category_id = NULL 
                   WHERE category_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([Article::STATUS_DRAFT, $id]);
            
            // Then delete the category
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            // Commit transaction
            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            // Rollback on error
            $this->pdo->rollBack();
            error_log("Error deleting category: " . $e->getMessage());
            throw $e;
        }
    }
    public function uploadImage($id, $imagePath)
{
    $sql = "UPDATE {$this->table} SET image = ? WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$imagePath, $id]);
}

public function deleteImage($id)
{
    $sql = "UPDATE {$this->table} SET image = NULL WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$id]);
}
}
?>
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

    // Remove duplicate methods since they're inherited from Model class
    // The parent class methods will use $this->table
}
?>
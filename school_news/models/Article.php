<?php

require_once __DIR__ . '/../core/Model.php';

class Article extends Model
{
    protected $table = 'articles';

    public function hasConnection()
    {
        return $this->pdo !== null;
    }

    public function getAll($limit = null)
    {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    ORDER BY a.created_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$limit]);
            } else {
                $stmt = $this->pdo->query($sql);
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("SQL Query: " . $sql);
            error_log("Results count: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("getAll Error: " . $e->getMessage());
            return [];
        }
    }

    public function getByCategory($categoryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDate($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE DATE(created_at) = ?");
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopular($limit = 3) {
        try {
            $sql = "SELECT a.*, 
                           c.name as category,
                           c.id as category_id,
                           COALESCE(a.views, 0) as views 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    ORDER BY COALESCE(a.views, 0) DESC, a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting popular articles: " . $e->getMessage());
            return [];
        }
    }

    public function getFeatured($limit = 4) {
        try {
            $sql = "SELECT a.*, c.name as category 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.featured = 1
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting featured articles: " . $e->getMessage());
            return [];
        }
    }

    public function incrementViews($id) {
        try {
            $sql = "UPDATE {$this->table} SET views = COALESCE(views, 0) + 1 WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error incrementing views: " . $e->getMessage());
            return false;
        }
    }

    public function getWithDetails($id) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id, u.username as author
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    LEFT JOIN users u ON a.user_id = u.id 
                    WHERE a.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting article details: " . $e->getMessage());
            return null;
        }
    }
}
?>
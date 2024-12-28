<?php

require_once __DIR__ . '/../core/Model.php';

class Article extends Model
{
    protected $table = 'articles';

    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_PRIVATE = 'private';
    const STATUS_PUBLISHED = 'published';
    const STATUS_DISQUALIFIED = 'disqualified';

    public function hasConnection()
    {
        return $this->pdo !== null;
    }

    public static function getStatusBadgeClass($status) {
        return match($status) {
            self::STATUS_DRAFT => 'warning',
            self::STATUS_REVIEWING => 'info',
            self::STATUS_PRIVATE => 'secondary',
            self::STATUS_PUBLISHED => 'success',
            self::STATUS_DISQUALIFIED => 'danger',
            default => 'light'
        };
    }

    public static function getAllStatuses() {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_REVIEWING => 'Under Review',
            self::STATUS_PRIVATE => 'Private',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_DISQUALIFIED => 'Disqualified'
        ];
    }

    public function getAll($limit = null) {
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

    public function getByCategory($categoryId){
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
            $sql = "SELECT a.*, 
                           c.name as category, 
                           c.id as category_id, 
                           u.username as author,
                           COALESCE(a.views, 0) as views
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

    public function getLatest($limit = 4) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting latest articles: " . $e->getMessage());
            return [];
        }
    }
    public function getLatestFeatured($limit = 3) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.featured = TRUE
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting latest featured articles: " . $e->getMessage());
            return [];
        }
    }
    
    public function getBreakingNews($limit = 5) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting breaking news: " . $e->getMessage());
            return [];
        }
    }
    
    public function getFeatured($limit = 4) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.featured = TRUE
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
    
    public function getPopular($limit = 3) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id,
                           COALESCE(a.views, 0) as views 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    ORDER BY views DESC, a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting popular articles: " . $e->getMessage());
            return [];
        }
    }
    public function countByUser($userId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting user articles: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalViewsByUser($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(views), 0) FROM {$this->table} WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total views: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalLikesByUser($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(likes), 0) FROM {$this->table} WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total likes: " . $e->getMessage());
            return 0;
        }
    }

    public function getByUser($userId, $limit = null) {
        try {
            $sql = "SELECT a.*, c.name as category 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.user_id = ?
                    ORDER BY a.created_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$userId, $limit]);
            } else {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$userId]);
            }
    
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getByUser Query: " . $sql);
            error_log("getByUser Results count: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting user articles: " . $e->getMessage());
            return [];
        }
    }
    
    public function getViewsStats($userId) {
        try {
            $sql = "SELECT DATE(created_at) as date, 
                   COALESCE(SUM(views), 0) as views 
                   FROM {$this->table} 
                   WHERE user_id = ? 
                   GROUP BY DATE(created_at) 
                   ORDER BY date DESC 
                   LIMIT 30";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting views stats: " . $e->getMessage());
            return [];
        }
    }
    
    public function getLikesStats($userId) {
        try {
            $sql = "SELECT DATE(created_at) as date, 
                   COALESCE(SUM(likes), 0) as likes 
                   FROM {$this->table} 
                   WHERE user_id = ? 
                   GROUP BY DATE(created_at) 
                   ORDER BY date DESC 
                   LIMIT 30";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting likes stats: " . $e->getMessage());
            return [];
        }
    }
    public function getRecentActivity($userId, $limit = 5) {
        try {
            $sql = "SELECT 
                        'comment' as type,
                        c.created_at,
                        a.title as article_title,
                        u.username as user_name
                    FROM comments c
                    JOIN articles a ON c.article_id = a.id
                    JOIN users u ON c.user_id = u.id
                    WHERE a.user_id = ?
                    
                    UNION ALL
                    
                    SELECT 
                        'view' as type,
                        created_at,
                        title as article_title,
                        NULL as user_name
                    FROM article_views
                    WHERE article_id IN (SELECT id FROM articles WHERE user_id = ?)
                    
                    ORDER BY created_at DESC
                    LIMIT ?";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent activity: " . $e->getMessage());
            return [];
        }
    }
}
?>
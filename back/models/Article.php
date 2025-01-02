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
    const LANG_AR = 'ar';
    const LANG_FR = 'fr';
    const LANG_EN = 'en';

    public function hasConnection()
    {
        return $this->pdo !== null;
    }

    public function query($sql, $params = [])
{
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        return [];
    }
}

public static function getAllStatuses()
{
    return [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_REVIEWING => 'Under Review',
        self::STATUS_PRIVATE => 'Private',
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_DISQUALIFIED => 'Disqualified'
    ];
}

    public function getByTitle($title)
{
    try {
        error_log("Getting article by title: " . $title);
        
        $sql = "SELECT a.*, c.name as category, u.username as author
                FROM {$this->table} a
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.title = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$title]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("Query result: " . print_r($result, true));
        
        return $result;
    } catch (PDOException $e) {
        error_log("Error getting article by title: " . $e->getMessage());
        error_log("SQL: " . $sql);
        return null;
    }
}

public function getReviewArticles() 
{
    try {
        $sql = "SELECT 
                a.*, 
                c.name as category,
                u.username as author,
                COALESCE(a.views, 0) as views,
                COALESCE(a.likes, 0) as likes
                FROM {$this->table} a 
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.status = ?
                ORDER BY a.created_at DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([self::STATUS_REVIEWING]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting review articles: " . $e->getMessage());
        return [];
    }
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

    // Get available statuses based on user role
    public static function getAvailableStatuses($userRole) {
        $allStatuses = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_REVIEWING => 'Under Review',
            self::STATUS_PRIVATE => 'Private',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_DISQUALIFIED => 'Disqualified'
        ];

        if ($userRole === 'admin') {
            return $allStatuses;
        }

        // For editors/writers, remove published and disqualified
        return array_filter($allStatuses, function($key) {
            return in_array($key, [self::STATUS_DRAFT, self::STATUS_REVIEWING, self::STATUS_PRIVATE]);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getAll($limit = null, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = ?
                    ORDER BY a.created_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status, $limit]);
            } else {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status]);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getAll Error: " . $e->getMessage());
            return [];
        }
    }

    public function getByCategory($categoryId, $status = self::STATUS_PUBLISHED){
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE category_id = ? AND status = ?");
        $stmt->execute([$categoryId, $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDate($date, $status = self::STATUS_PUBLISHED) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE DATE(created_at) = ? AND status = ?");
        $stmt->execute([$date, $status]);
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

    
    public function getLatest($limit = 4, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = ?
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting latest articles: " . $e->getMessage());
            return [];
        }
    }

    public function getLatestFeatured($limit = 3, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.featured = TRUE AND a.status = ?
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting latest featured articles: " . $e->getMessage());
            return [];
        }
    }
    
    public function getBreakingNews($limit = 5, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = ?
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting breaking news: " . $e->getMessage());
            return [];
        }
    }
    
    public function getFeatured($limit = 4, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.featured = TRUE AND a.status = ?
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting featured articles: " . $e->getMessage());
            return [];
        }
    }
    
    public function getPopular($limit = 3, $status = self::STATUS_PUBLISHED) {
        try {
            $sql = "SELECT a.*, c.name as category, c.id as category_id,
                           COALESCE(a.views, 0) as views 
                    FROM {$this->table} a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = ?
                    ORDER BY views DESC, a.created_at DESC 
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $limit]);
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
    // Add these methods to Article class
public function getViewsTrend($userId) 
{
    try {
        $sql = "SELECT 
                (SELECT COALESCE(SUM(views), 0) FROM articles 
                 WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)) as current_views,
                (SELECT COALESCE(SUM(views), 0) FROM articles 
                 WHERE user_id = ? AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)) as previous_views";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['previous_views'] == 0) return 0;
        return round((($result['current_views'] - $result['previous_views']) / $result['previous_views']) * 100, 1);
    } catch (PDOException $e) {
        error_log("Error calculating views trend: " . $e->getMessage());
        return 0;
    }
}

public function getEngagementRate($userId)
{
    try {
        $sql = "SELECT 
                COALESCE(AVG((views + likes + comments) / NULLIF(views, 0) * 100), 0) as engagement_rate
                FROM articles WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return round($stmt->fetchColumn(), 2);
    } catch (PDOException $e) {
        error_log("Error calculating engagement rate: " . $e->getMessage());
        return 0;
    }
}

public function getTopArticles($userId)
{
    try {
        $sql = "SELECT *,
                ((views + likes + comments) / NULLIF(views, 0) * 100) as engagement
                FROM articles 
                WHERE user_id = ?
                ORDER BY views DESC
                LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting top articles: " . $e->getMessage());
        return [];
    }
}
// Add these methods to the Article class
public function getTotalEngagementByUser($userId)
{
    try {
        $sql = "SELECT COALESCE(SUM(views + likes + comments), 0) as total_engagement 
                FROM {$this->table} 
                WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting total engagement: " . $e->getMessage());
        return 0;
    }
}

public function getEngagementTrend($userId)
{
    try {
        $sql = "SELECT 
                (SELECT COALESCE(SUM(views + likes + comments), 0) 
                 FROM articles 
                 WHERE user_id = ? 
                 AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)) as current_engagement,
                (SELECT COALESCE(SUM(views + likes + comments), 0) 
                 FROM articles 
                 WHERE user_id = ? 
                 AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) 
                 AND DATE_SUB(NOW(), INTERVAL 1 MONTH)) as previous_engagement";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['previous_engagement'] == 0) return 0;
        return round((($result['current_engagement'] - $result['previous_engagement']) / $result['previous_engagement']) * 100, 1);
    } catch (PDOException $e) {
        error_log("Error calculating engagement trend: " . $e->getMessage());
        return 0;
    }
}

public function getPerformanceLabels($userId)
{
    try {
        $sql = "SELECT DISTINCT DATE(created_at) as date 
                FROM {$this->table} 
                WHERE user_id = ? 
                ORDER BY date DESC 
                LIMIT 30";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return array_map(function($row) {
            return date('M d', strtotime($row['date']));
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        error_log("Error getting performance labels: " . $e->getMessage());
        return [];
    }
}

public function getViewsData($userId)
{
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
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'views');
    } catch (PDOException $e) {
        error_log("Error getting views data: " . $e->getMessage());
        return [];
    }
}

public function getEngagementData($userId)
{
    try {
        $sql = "SELECT DATE(created_at) as date, 
                COALESCE(SUM(views + likes + comments), 0) as engagement 
                FROM {$this->table} 
                WHERE user_id = ? 
                GROUP BY DATE(created_at) 
                ORDER BY date DESC 
                LIMIT 30";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'engagement');
    } catch (PDOException $e) {
        error_log("Error getting engagement data: " . $e->getMessage());
        return [];
    }
}
}
?>

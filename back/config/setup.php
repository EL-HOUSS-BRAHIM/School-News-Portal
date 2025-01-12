<?php
require_once 'database.php';

class DatabaseSetup {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTables() {
        try {
            $this->pdo->beginTransaction();
            
            $this->createUsersTable();
            $this->createCategoriesTable();
            $this->createArticlesTable();
            $this->createCommentsTable();
            $this->createSettingsTable();
            $this->createPopularArticlesTable();
            $this->addConstraints();
            $this->updateArticlesTable(); // Add this line
            
            $this->pdo->commit();
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    private function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id VARCHAR(36) NOT NULL PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'editor', 'user') DEFAULT 'user',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
    }
    
    private function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id VARCHAR(36) NOT NULL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            image VARCHAR(255)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
    }

    private function createArticlesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS articles (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            image VARCHAR(255) DEFAULT NULL,
            category_id VARCHAR(36) DEFAULT NULL,
            user_id VARCHAR(36) DEFAULT NULL,
            views INT DEFAULT 0,
            featured TINYINT(1) DEFAULT 0,
            breaking TINYINT(1) DEFAULT 0,
            status ENUM('draft', 'reviewing', 'private', 'published', 'disqualified') DEFAULT 'draft',
            likes INT DEFAULT 0,
            language VARCHAR(2) DEFAULT 'fr',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_article_title (title)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->pdo->exec($sql);
        
        // Set table character set and collation
        $this->pdo->exec("ALTER TABLE articles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    private function createCommentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS comments (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            content TEXT NOT NULL,
            article_id INT NOT NULL,
            user_id VARCHAR(36) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
    }

    private function createSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value TEXT DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
        $this->pdo->exec($sql);
    }

    private function createPopularArticlesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS popular_articles (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            category_id VARCHAR(36) DEFAULT NULL,
            user_id VARCHAR(36) DEFAULT NULL,
            views INT(11) DEFAULT 0,
            featured TINYINT(1) DEFAULT 0,
            breaking TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
    }

    private function addConstraints() {
        $constraints = [
            // Articles foreign keys
            "ALTER TABLE articles 
             ADD CONSTRAINT fk_article_category 
             FOREIGN KEY (category_id) REFERENCES categories(id) 
             ON DELETE SET NULL",
    
            "ALTER TABLE articles 
             ADD CONSTRAINT fk_article_user 
             FOREIGN KEY (user_id) REFERENCES users(id) 
             ON DELETE SET NULL",
    
            // Comments foreign keys - Note INT for article_id
            "ALTER TABLE comments 
             ADD CONSTRAINT fk_comment_article 
             FOREIGN KEY (article_id) REFERENCES articles(id) 
             ON DELETE CASCADE",
    
            "ALTER TABLE comments 
             ADD CONSTRAINT fk_comment_user 
             FOREIGN KEY (user_id) REFERENCES users(id) 
             ON DELETE CASCADE",
    
            // Popular articles foreign keys
            "ALTER TABLE popular_articles 
             ADD CONSTRAINT fk_popular_category 
             FOREIGN KEY (category_id) REFERENCES categories(id) 
             ON DELETE SET NULL",
    
            "ALTER TABLE popular_articles 
             ADD CONSTRAINT fk_popular_user 
             FOREIGN KEY (user_id) REFERENCES users(id) 
             ON DELETE SET NULL"
        ];
    
        foreach ($constraints as $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                if ($e->getCode() != '42121') {
                    throw $e;
                }
            }
        }
    }

    private function updateArticlesTable() {
        try {
            // Ensure proper character set and collation
            $this->pdo->exec("ALTER TABLE articles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("ALTER TABLE articles MODIFY title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Add title index if not exists
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_article_title ON articles (title)");
        } catch (PDOException $e) {
            // Ignore if index already exists
            if ($e->getCode() != '42121') {
                throw $e;
            }
        }
    }
}
?>
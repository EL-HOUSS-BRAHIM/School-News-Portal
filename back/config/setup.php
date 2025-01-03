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
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'editor', 'user') DEFAULT 'user',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
        $this->pdo->exec($sql);
    }

    private function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            image VARCHAR(255)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
        $this->pdo->exec($sql);
    }

    private function createArticlesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS articles (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            image VARCHAR(255) DEFAULT NULL,
            category_id INT DEFAULT NULL,
            user_id INT DEFAULT NULL,
            views INT DEFAULT 0,
            featured TINYINT(1) DEFAULT 0,
            breaking TINYINT(1) DEFAULT 0,
            status ENUM('draft', 'reviewing', 'private', 'published', 'disqualified') DEFAULT 'draft',
            likes INT DEFAULT 0,
            language VARCHAR(2) DEFAULT 'fr',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->pdo->exec($sql);
    }

    private function createCommentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS comments (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            content TEXT NOT NULL,
            article_id INT(11) NOT NULL,
            user_id INT(11) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
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
            id INT(11) DEFAULT NULL,
            title VARCHAR(255) DEFAULT NULL,
            content TEXT DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            category_id INT(11) DEFAULT NULL,
            user_id INT(11) DEFAULT NULL,
            views INT(11) DEFAULT NULL,
            featured TINYINT(1) DEFAULT NULL,
            breaking TINYINT(1) DEFAULT NULL,
            created_at DATETIME DEFAULT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
        $this->pdo->exec($sql);
    }
}
?>
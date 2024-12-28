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
            $this->createPopularArticlesView();
            
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'editor', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    private function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    private function createArticlesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255),
            category_id INT,
            user_id INT,
            status ENUM('draft', 'reviewing', 'private', 'published', 'disqualified') DEFAULT 'draft',
            views INT DEFAULT 0,
            likes INT DEFAULT 0,
            featured BOOLEAN DEFAULT FALSE,
            breaking BOOLEAN DEFAULT FALSE,
            created_at DATETIME,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        $this->pdo->exec($sql);

        // Set default for existing rows
        $this->pdo->exec("UPDATE articles SET created_at = NOW() WHERE created_at IS NULL");
    }

    private function createCommentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            content TEXT NOT NULL,
            article_id INT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        $this->pdo->exec($sql);
    }

    private function createSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    private function createPopularArticlesView() {
        $sql = "CREATE OR REPLACE VIEW popular_articles AS 
                SELECT * FROM articles 
                ORDER BY views DESC, created_at DESC 
                LIMIT 3";
        $this->pdo->exec($sql);
    }
}
?>
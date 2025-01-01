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
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'editor', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $this->pdo->exec($sql);
    }

    private function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $this->pdo->exec($sql);
    }

    private function createArticlesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            image VARCHAR(255),
            category_id INT,
            user_id INT,
            views INT DEFAULT 0,
            featured TINYINT(1) DEFAULT 0,
            breaking TINYINT(1) DEFAULT 0,
            status ENUM('draft', 'reviewing', 'private', 'published', 'disqualified') DEFAULT 'draft',
            likes INT DEFAULT 0,
            created_at DATETIME,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $this->pdo->exec($sql);
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
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $this->pdo->exec($sql);
    }

    private function createSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $this->pdo->exec($sql);
    }

    private function addConstraints() {
        $sql = "ALTER TABLE articles
                ADD CONSTRAINT articles_ibfk_1 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL,
                ADD CONSTRAINT articles_ibfk_2 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
                ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL,
                ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE";
        $this->pdo->exec($sql);

        $sql = "ALTER TABLE comments
                ADD CONSTRAINT comments_ibfk_1 FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE,
                ADD CONSTRAINT comments_ibfk_2 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE";
        $this->pdo->exec($sql);
    }
}
?>
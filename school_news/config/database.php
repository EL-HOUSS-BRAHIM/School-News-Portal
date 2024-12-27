<?php
require_once __DIR__ . '/setup.php';
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');
error_reporting(E_ALL);
class Database {
    private static $instance = null;
    private $pdo = null;
    private $config = [];
    
    private function __construct() {
        $this->config = require __DIR__ . '/database.config.php';
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $this->config['host'],
                $this->config['dbname'],
                $this->config['charset']
            );
            
            $this->pdo = new PDO(
                $dsn, 
                $this->config['username'], 
                $this->config['password'], 
                $this->config['options']
            );
            
            // Test connection
            $this->pdo->query("SELECT 1");
            
            return true;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw $e;
        }
    }

    // Remove createDatabase() method since we can't create databases on shared hosting

    private function initializeDatabase() {
        try {
            $setup = new DatabaseSetup($this->pdo);
            $setup->createTables();
        } catch (PDOException $e) {
            $this->handleError($e, "Failed to initialize database tables");
        }
    }

    private function handleError(PDOException $e, $context = '') {
        $message = $context ? "$context: " : '';
        $message .= $e->getMessage();
        
        error_log($message);
        
        // In production, don't expose error details
        if (getenv('APP_ENV') === 'production') {
            throw new Exception('Database error occurred. Please try again later.');
        } else {
            throw new Exception($message);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }

    public function __destruct() {
        $this->pdo = null;
    }

    // Prevent cloning of the instance
    private function __clone() {
        
    }

    // Prevent unserializing of the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>
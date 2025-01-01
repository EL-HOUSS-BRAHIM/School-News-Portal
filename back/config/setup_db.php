<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../core/Helpers.php'; // Ensure the generateUUID function is available

try {
    echo "Starting database setup...\n";
    
    // Get database instance
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Make sure no transaction is active
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Initialize database structure
    $setup = new DatabaseSetup($pdo);
    $setup->createTables();
    
    echo "Database tables created successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
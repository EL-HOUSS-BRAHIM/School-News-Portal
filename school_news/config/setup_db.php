<?php
require_once __DIR__ . '/database.php';

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
    
    // Start new transaction for data insertion
    $pdo->beginTransaction();
    
    try {
        // Create initial admin user
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $password, 'admin']);
        echo "Admin user created successfully!\n";
        
        // Insert default categories
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $categories = [
            ['World News', 'world-news'],
            ['Technology', 'technology'],
            ['Sports', 'sports'],
            ['Education', 'education']
        ];
        
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
        echo "Default categories created successfully!\n";
        
        // Commit transaction
        $pdo->commit();
        echo "Database setup completed successfully!\n";
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
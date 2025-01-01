<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../core/Helpers.php'; // Ensure the generateUUID function is available

try {
    echo "Starting data insertion...\n";
    
    // Get database instance
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Start new transaction for data insertion
    $pdo->beginTransaction();
    echo "Transaction started...\n";
    
    try {
        // Create initial admin user
        $adminId = generateUUID();
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (id, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$adminId, 'bross', $password, 'admin']);
        echo "Admin user created successfully!\n";
        
        // Insert default categories in French
        $stmt = $pdo->prepare("INSERT INTO categories (id, name, slug) VALUES (?, ?, ?)");
        $categories = [
            [generateUUID(), 'Actualités Scolaires', 'actualites-scolaires'],
            [generateUUID(), 'Vie Étudiante', 'vie-etudiante'],
            [generateUUID(), 'Événements', 'evenements'],
            [generateUUID(), 'Activités Parascolaires', 'activites-parascolaires'],
            [generateUUID(), 'Examens et Résultats', 'examens-resultats'],
            [generateUUID(), 'Sports et Loisirs', 'sports-loisirs']
        ];
        
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
        echo "Categories created successfully!\n";
        
        // Commit transaction
        $pdo->commit();
        echo "Transaction committed successfully!\n";
        echo "Data insertion completed successfully!\n";
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
            echo "Transaction rolled back due to error.\n";
        }
        throw $e;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
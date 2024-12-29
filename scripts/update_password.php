<?php

try {
    // Load database configuration
    $dbConfig = require_once __DIR__ . '/../config/database.config.php';
    
    // Create PDO connection
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Hash the new password
    $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
    
    // Update the password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $success = $stmt->execute([$hashedPassword, 'yassine']);
    
    if ($success && $stmt->rowCount() > 0) {
        echo "Password updated successfully!\n";
    } else {
        echo "User not found or password unchanged.\n";
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
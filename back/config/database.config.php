<?php
return [
    'host' => $_ENV['DB_HOST'] ?? 'sql307.infinityfree.com',
    'dbname' => $_ENV['DB_NAME'] ?? 'if0_38006148_news',
    'username' => $_ENV['DB_USER'] ?? 'if0_38006148',
    'password' => $_ENV['DB_PASS'] ?? 'bffbst2TfR3',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true,
        PDO::ATTR_TIMEOUT => 3,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ]
];
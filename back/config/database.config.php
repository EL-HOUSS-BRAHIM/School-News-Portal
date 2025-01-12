<?php
return [
    'host' => $_ENV['DB_HOST'] ?? '',
    'dbname' => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASS'] ?? '',
    'port' => $_ENV['DB_PORT'] ?? '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true,
        PDO::ATTR_TIMEOUT => 3,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/../storage/sessions/ca.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ],
    'ssl' => [
        'enabled' => true,
        'required' => true,
        'ca' => __DIR__ . '/../storage/sessions/ca.pem',
        'verify_server_cert' => false
    ]
];
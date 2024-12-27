<?php
return [
    'host' => 'sql7.freemysqlhosting.net',
    'dbname' => 'sql7754447',
    'username' => 'sql7754447',
    'password' => 'WAexlpxk3b',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true,
        PDO::ATTR_TIMEOUT => 5
    ]
];
<?php

echo "Starting installation...\n";

// Configuration
$repoUrl = 'git@github.com:ELGOUMRIYASSINE/newproject.git';
$installPath = __DIR__;
$requiredFolders = [
    'assets',
    'assets/img',
    'back',
    'back/views',
    'back/controllers',
    'back/models',
    'back/config',
    'back/layouts'
];

// Functions
function executeCommand($command) {
    echo "Executing: $command\n";
    exec($command, $output, $returnValue);
    return $returnValue === 0;
}

function createFolders($folders) {
    foreach ($folders as $folder) {
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
            echo "Created folder: $folder\n";
        }
    }
}

// Main installation
try {
    // Check Git installation
    if (!executeCommand('which git')) {
        throw new Exception('Git is not installed');
    }

    // Clone repository
    if (!file_exists('.git')) {
        if (!executeCommand("git clone $repoUrl .")) {
            throw new Exception('Failed to clone repository');
        }
    }

    // Create required folders
    createFolders($requiredFolders);

    // Set permissions
    executeCommand('chmod -R 755 .');
    executeCommand('chmod -R 777 assets/img');

    // Create .env if not exists
    if (!file_exists('.env')) {
        copy('.env.example', '.env');
        echo "Created .env file\n";
    }

    // Install composer dependencies if composer.json exists
    if (file_exists('composer.json')) {
        executeCommand('composer install');
    }

    echo "Installation completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
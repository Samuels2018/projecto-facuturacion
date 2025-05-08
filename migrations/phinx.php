<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../'  );
$dotenv->load();

return [
    'paths' => [
        'migrations' => __DIR__ . '/db',
    ],
    'environments' => [
        'default_migration_table' => null,
        'default_environment' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST_LOG'],
            'name' => $_ENV['DB_NAME_LOG'],
            'user' => $_ENV['DB_USER_LOG'],
            'pass' => $_ENV['DB_PASS_LOG'], 
            'port' => 3306,
            'charset' => 'utf8mb4',
        ],
    
   
    ],
];
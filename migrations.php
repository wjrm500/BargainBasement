<?php

use app\core\Application;
use Dotenv\Dotenv;

// Autoloading

require_once __DIR__ . '/vendor/autoload.php';

// Environment variables

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create new Application instance

$dbConfig = [
    'DB_DSN'      => $_ENV['DB_DSN'],
    'DB_USER'     => $_ENV['DB_USER'],
    'DB_PASSWORD' => $_ENV['DB_PASSWORD'],
];

$app = new Application($dbConfig);

// Apply migrations

$app->database->applyMigrations();
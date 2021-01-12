<?php

if ($_SERVER['REQUEST_URI'] === '/migrations.php') {
    include __DIR__ . '/../migrations.php';
    exit();
}

use app\controllers\AccountController;
use app\controllers\HomeController;
use app\core\Application;
use Dotenv\Dotenv;

// Autoloading

require_once __DIR__ . '/../vendor/autoload.php';

// Environment variables

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Create new Application instance

$dbConfig = [
    'DB_DSN'      => $_ENV['DB_DSN'],
    'DB_USER'     => $_ENV['DB_USER'],
    'DB_PASSWORD' => $_ENV['DB_PASSWORD'],
];

$app = new Application($dbConfig);

// Set routes

$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/login', [AccountController::class, 'login']);
$app->router->post('/login', [AccountController::class, 'login']);

$app->router->get('/register', [AccountController::class, 'register']);
$app->router->post('/register', [AccountController::class, 'register']);

$app->router->get('/logout', [AccountController::class, 'logout']);

$app->router->get('/profile', [AccountController::class, 'profile']);

// Run Application

$app->run();
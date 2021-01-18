<?php

/**
 * @var app\core\Application $app;
 */

use app\controllers\AccountController;
use app\controllers\admin\AdminProductController;
use app\controllers\HomeController;

// Customer routes

$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/login', [AccountController::class, 'login']);
$app->router->post('/login', [AccountController::class, 'login']);

$app->router->get('/register', [AccountController::class, 'register']);
$app->router->post('/register', [AccountController::class, 'register']);

$app->router->get('/logout', [AccountController::class, 'logout']);

$app->router->get('/profile', [AccountController::class, 'profile']);

// Admin routes

$app->router->get('/admin', [HomeController::class, 'adminIndex']);

$app->router->get('/admin/product', [AdminProductController::class, 'index']);
$app->router->get('/admin/product/add', [AdminProductController::class, 'addProduct']);
$app->router->post('/admin/product/add', [AdminProductController::class, 'saveAddProduct']);
$app->router->get('/admin/product/{id}/edit', [AdminProductController::class, 'editProduct']);
$app->router->post('/admin/product/{id}/edit', [AdminProductController::class, 'saveEditProduct']);
$app->router->get('/admin/product/{id}/delete', [AdminProductController::class, 'deleteProduct']);
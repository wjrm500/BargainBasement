<?php

/**
 * @var app\core\Application $app;
 */

use app\controllers\AccountController;
use app\controllers\admin\AdminProductController;
use app\controllers\HomeController;

// Customer routes

$app->router->get('/', [HomeController::class, 'index']);

$app->router->controller('/login', [AccountController::class, 'login']);

$app->router->controller('/register', [AccountController::class, 'register']);

$app->router->get('/logout', [AccountController::class, 'logout']);

$app->router->get('/profile', [AccountController::class, 'profile']);

// Admin routes

$app->router->get('/admin', [HomeController::class, 'adminIndex']);

$app->router->get('/admin/product', [AdminProductController::class, 'index']);
$app->router->controller('/admin/product/add', [AdminProductController::class, 'addProduct']);
$app->router->controller('/admin/product/{id}/edit', [AdminProductController::class, 'editProduct']);
$app->router->get('/admin/product/{id}/delete', [AdminProductController::class, 'deleteProduct']);
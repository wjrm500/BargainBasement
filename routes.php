<?php

/**
 * @var app\core\Application $app;
 */

use app\controllers\AccountController;
use app\controllers\admin\AdminProductCategoryController;
use app\controllers\admin\AdminProductController;
use app\controllers\HomeController;
use app\controllers\ShopController;

// Customer routes

$app->router->get('/', [HomeController::class, 'index']);

$app->router->controller('/login', [AccountController::class, 'login']);

$app->router->controller('/register', [AccountController::class, 'register']);

$app->router->get('/logout', [AccountController::class, 'logout']);

$app->router->get('/profile', [AccountController::class, 'profile']);

$app->router->get('/shop', [ShopController::class, 'index']);
$app->router->get('/shop/ajax/get-templates', [ShopController::class, 'getTemplates']);
$app->router->post('/shop/ajax/persist-basket', [ShopController::class, 'persistBasket']);

// For checkout
$app->router->get('/shop/checkout', [ShopController::class, 'getCheckout']);
$app->router->post('/shop/checkout', [ShopController::class, 'postCheckout']);
$app->router->get('/shop/ajax/basket-data', [ShopController::class, 'getBasketData']);
$app->router->post('/shop/ajax/basket-data', [ShopController::class, 'postBasketData']);

// Image route

$app->router->get('/images/{image}', '/images/{image}');

// Admin routes

$app->router->get('/admin', [HomeController::class, 'adminIndex']);

$app->router->get('/admin/product', [AdminProductController::class, 'index']);
$app->router->controller('/admin/product/add', [AdminProductController::class, 'addProduct']);
$app->router->controller('/admin/product/{id}/edit', [AdminProductController::class, 'editProduct']);
$app->router->get('/admin/product/{id}/delete', [AdminProductController::class, 'deleteProduct']);
$app->router->post('/admin/product/search', [AdminProductController::class, 'search']);

$app->router->get('/admin/product-category', [AdminProductCategoryController::class, 'index']);
$app->router->controller('/admin/product-category/add', [AdminProductCategoryController::class, 'addProductCategory']);
$app->router->controller('/admin/product-category/{id}/edit', [AdminProductCategoryController::class, 'editProductCategory']);
$app->router->get('/admin/product-category/{id}/delete', [AdminProductCategoryController::class, 'deleteProductCategory']);
$app->router->post('/admin/product-category/search', [AdminProductCategoryController::class, 'search']);
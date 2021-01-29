<?php

use app\core\Application;

$app = Application::$app;
$isAdmin = $app->hasUser() && $app->getUser()->isAdmin();

?>

<nav class="navbar navbar-light bg-light px-5">
    <div id="header-left" class="col-sm-4 col-12 my-1">
        <div id="header-left-text-container">
            <a class="h1 font-weight-bold text-decoration-none" href="/">
                Bargain Basement
            </a>
        </div>
    </div>
    <div id="header-center" class="col-sm-4 col-12 my-1">
        <div id="secondary-navbar" class="rounded">
            <ul class="nav">
                <li id="nav-item-shop" class="text-center my-1 col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
                    <span class="text"><a class="btn" href="#">Shop</a></span>
                    <span class="icon"><a class="btn" href="#"><i class="fas fa-shopping-cart"></i></a></span>
                </li>
                <li id="nav-item-other" class="text-center my-1 col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
                    <span class="text"><a class="btn" href="#">Other</a></span>
                    <span class="icon"><a class="btn" href="#"><i class="fas fa-envelope-open"></i></a></span>
                </li>
                <?php if ($isAdmin): ?>
                    <li id="nav-item-admin" class="text-center my-1 col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
                        <span class="text"><a class="btn text-primary" href="/admin">Admin</a></span>
                        <span class="icon"><a class="btn text-primary" href="/admin"><i class="fas fa-user-lock"></i></a></span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div id="header-right" class="col-sm-4 col-12 my-1 nav">
        <?php if (!$app->hasUser()): ?>
            <a href="/login" type="button" class="btn btn-secondary header-right-btn">Login</a>
            <a href="/register" type="button" class="btn btn-primary header-right-btn">Register</a>
        <?php else: ?>
            <a href="/account" type="button" class="btn btn-secondary header-right-btn">Account</a>
            <a href="/logout" type="button" class="btn btn-danger header-right-btn">Logout</a>
        <?php endif; ?>
    </div>
</nav>
<?php

use app\core\Application;

$app = Application::$app;
?>

<nav class="navbar bg-light px-5">
    <div id="company-name" class="col-1">
        <a class="h1 font-weight-bold text-decoration-none" href="/">
            BB
        </a>
    </div>

    <div id="secondary-navbar" class="container col-6 rounded">
        <ul class="nav col-12">
            <li class="text-center my-1 col-12 col-md-4">
                <a class="btn" href="#"><i class="fas fa-shopping-cart"></i></a>
            </li>
            <li class="text-center my-1 col-12 col-md-4">
                <a class="btn" href="#"><i class="fas fa-envelope-open"></i></a>
            </li>
            <li class="text-center my-1 bg-primary border border-white rounded col-12 col-md-4">
                <a class="btn text-white" href="/admin"><i class="fas fa-user-lock"></i></a>
            </li>
        </ul>
    </div>

    <div class="col-5" style="text-align: right">
    <?php if (!$app->hasUser()): ?>
        <a href="/login" type="button" class="btn btn-secondary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Login</a>
        <a href="/register" type="button" class="btn btn-primary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Register</a>   
    <?php else: ?>
        <a href="/profile" type="button" class="btn btn-secondary col-md-5 col-12 m-1">Profile</a>
        <a href="/logout" type="button" class="btn btn-danger col-md-5 col-12 m-1">Logout</a>
    <?php endif; ?>
    </div>
</nav>
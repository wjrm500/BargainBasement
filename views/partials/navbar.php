<?php

use app\core\Application;

$app = Application::$app;
?>

<nav class="navbar bg-light px-5">
    <div id="company-name" class="col-lg-9 col-md-8 col-sm-6 col-12 p-2">
        <a class="h1 font-weight-bold text-decoration-none" href="/">
            Bargain Basement
        </a>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 text-center">
    <?php if (!$app->hasUser()): ?>
        <a href="/login" type="button" class="btn btn-secondary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Login</a>
        <a href="/register"button type="button" class="btn btn-primary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Register</a>   
    <?php else: ?>
        <a href="/profile" type="button" class="btn btn-secondary col-md-5 col-12 m-1">Profile</a>
        <a href="/logout" type="button" class="btn btn-danger col-md-5 col-12 m-1">Logout</a>
    <?php endif; ?>
    </div>
</nav>
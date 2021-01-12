<?php

use app\core\Application;

$app = Application::$app;
?>

<nav class="navbar bg-light">
    <div class="col-10">
        <a class="h1 font-weight-bold text-decoration-none" href="/">
            Bargain Basement
        </a>
    </div>
    <div class="col-2">
    <?php if (!$app->hasUser()): ?>
        <a href="/login" type="button" class="btn btn-secondary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Login</a>
        <a href="/register"button type="button" class="btn btn-primary col-5
        <?= in_array($app->request->getPath(), ['/login', '/register']) ? 'disabled' : ''; ?>
        ">Register</a>   
    <?php else: ?>
        <a href="/profile" type="button" class="btn btn-secondary col-5">Profile</a>
        <a href="/logout" type="button" class="btn btn-danger col-5">Logout</a>
    <?php endif; ?>
    </div>
</nav>
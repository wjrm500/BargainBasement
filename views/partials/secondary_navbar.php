<?php

use app\core\Application;

$app = Application::$app;
$isAdmin = $app->hasUser() && $app->getUser()->isAdmin();

?>

<div id="secondary-navbar" class="container col-5 rounded">
    <ul class="nav">
        <li class="text-center my-1 col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
            <span class="text"><a class="btn" href="#">Groceries</a></span>
            <span class="icon"><a class="btn" href="#"><i class="fas fa-shopping-cart"></i></a></span>
        </li>
        <li class="text-center my-1 col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
            <span class="text"><a class="btn" href="#">Contact us</a></span>
            <span class="icon"><a class="btn" href="#"><i class="fas fa-envelope-open"></i></a></span>
        </li>
        <?php if ($isAdmin): ?>
            <li class="text-center my-1 bg-primary border border-white rounded col-12 <?= $isAdmin ? 'col-md-4' : 'col-md-6' ?>">
                <span class="text"><a class="btn text-white" href="/admin">Admin</a></span>
                <span class="icon"><a class="btn text-white" href="/admin"><i class="fas fa-user-lock"></i></a></span>
            </li>
        <?php endif; ?>
    </ul>
</div>
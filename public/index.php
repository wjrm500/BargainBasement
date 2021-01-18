<?php

if ($_SERVER['REQUEST_URI'] === '/migrations.php') {
    require_once __DIR__ . '/../migrations.php';
    exit();
}

require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../routes.php';

$app->run();
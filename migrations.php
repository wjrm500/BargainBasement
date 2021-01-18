<?php

require_once 'bootstrap.php';

// Apply migrations

if (isset($argv[1]) && $argv[1] === 'reverse') {
    $app->database->reverseMigrations($argv[2] ?? '');
    exit();
}

$app->database->applyMigrations();
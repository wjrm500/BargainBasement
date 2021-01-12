<?php

namespace app\core;

use app\core\db\Database;

abstract class Migration
{
    public Database $database;

    public function __construct()
    {
        $this->database = Application::$app->database;
    }

    abstract public function up();

    abstract public function down();
}
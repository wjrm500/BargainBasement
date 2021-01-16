<?php

use app\core\Migration;

class m0002_add_admin_column_to_users_table extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE users ADD COLUMN admin INT DEFAULT 0";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE users DROP COLUMN admin";
        $this->database->query($sql);
    }
}
<?php

use app\core\Migration;

class m0005_add_country_id_column_to_users_table extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE users ADD COLUMN country_id INT DEFAULT NULL";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE users DROP COLUMN country_id";
        $this->database->query($sql);
    }
}
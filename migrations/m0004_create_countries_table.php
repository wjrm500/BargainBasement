<?php

use app\core\Migration;

class m0004_create_countries_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS countries
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS countries";
        $this->database->query($sql);
    }
}
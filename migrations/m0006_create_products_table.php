<?php

use app\core\Migration;

class m0006_create_products_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS products
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255),
                    image VARCHAR(255),
                    description TEXT
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS products";
        $this->database->query($sql);
    }
}
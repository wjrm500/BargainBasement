<?php

use app\core\Migration;

class m0015_create_product_categories_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS product_categories
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS product_categories";
        $this->database->query($sql);
    }
}
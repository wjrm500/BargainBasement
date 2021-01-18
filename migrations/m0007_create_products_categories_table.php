<?php

use app\core\Migration;

class m0007_create_products_categories_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS products_categories
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT,
                    category_id INT
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS products_categories";
        $this->database->query($sql);
    }
}
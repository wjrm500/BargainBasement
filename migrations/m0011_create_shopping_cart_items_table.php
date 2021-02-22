<?php

use app\core\Migration;

class m0011_create_shopping_cart_items_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS shopping_cart_items
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    shopping_cart_id INT,
                    product_id INT,
                    quantity INT,
                    INDEX (shopping_cart_id),
                    INDEX (product_id)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS shopping_cart_items";
        $this->database->query($sql);
    }
}
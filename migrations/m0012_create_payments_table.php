<?php

use app\core\Migration;

class m0012_create_payments_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS payments
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    shopping_cart_id INT,
                    payment_made_at DATETIME,
                    price_paid FLOAT,
                    INDEX (shopping_cart_id)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS payments";
        $this->database->query($sql);
    }
}
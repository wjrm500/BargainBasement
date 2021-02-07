<?php

use app\core\Migration;

class m0009_add_price_and_weight_to_products_table extends Migration
{
    public function up()
    {
        $sql = "
            ALTER TABLE products
            ADD COLUMN price FLOAT DEFAULT NULL,
            ADD COLUMN weight INT DEFAULT NULL
        ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "
            ALTER TABLE products
            DROP COLUMN price,
            DROP COLUMN weight
        ";
        $this->database->query($sql);
    }
}
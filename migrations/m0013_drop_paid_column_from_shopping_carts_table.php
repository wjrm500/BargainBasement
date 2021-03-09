<?php

use app\core\Migration;

class m0013_drop_paid_column_from_shopping_carts_table extends Migration
{
    public function up()
    {
        $sql = "
            ALTER TABLE shopping_carts
            DROP COLUMN paid
        ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "
            ALTER TABLE products
            ADD COLUMN paid INT DEFAULT NULL
        ";
        $this->database->query($sql);
    }
}
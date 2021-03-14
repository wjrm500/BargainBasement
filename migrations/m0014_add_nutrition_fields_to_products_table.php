<?php

use app\core\Migration;

class m0014_add_nutrition_fields_to_products_table extends Migration
{
    public function up()
    {
        $sql = "
            ALTER TABLE products
            ADD COLUMN energy_kcal INT DEFAULT NULL,
            ADD COLUMN fat_g FLOAT DEFAULT NULL,
            ADD COLUMN saturates_g FLOAT DEFAULT NULL,
            ADD COLUMN sugars_g FLOAT DEFAULT NULL,
            ADD COLUMN salt_g FLOAT DEFAULT NULL
        ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "
            ALTER TABLE products
            DROP COLUMN energy_kcal,
            DROP COLUMN fat_g,
            DROP COLUMN saturates_g,
            DROP COLUMN sugars_g,
            DROP COLUMN salt_g
        ";
        $this->database->query($sql);
    }
}
<?php

use app\core\Migration;

class m0010_create_shopping_carts_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS shopping_carts
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    paid INT,
                    INDEX (user_id)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS shopping_carts";
        $this->database->query($sql);
    }
}
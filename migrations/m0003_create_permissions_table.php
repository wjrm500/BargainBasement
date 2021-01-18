<?php

use app\core\Migration;

class m0003_create_permissions_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS permissions
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255),
                    href VARCHAR(255)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS permissions";
        $this->database->query($sql);
    }
}
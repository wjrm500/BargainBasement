<?php

use app\core\Migration;

class m0004_create_admin_permissions_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS admin_permissions
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
        $sql = "DROP TABLE IF EXISTS admin_permissions";
        $this->database->query($sql);
    }
}
<?php

use app\core\Migration;

class m0002_create_users_permissions_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_permissions
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    permission_id INT
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS users_permissions";
        $this->database->query($sql);
    }
}
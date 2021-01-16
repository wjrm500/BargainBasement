<?php

use app\core\Migration;

class m0003_create_admin_users_permissions_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS admin_users_permissions
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
        $sql = "DROP TABLE IF EXISTS admin_users_permissions";
        $this->database->query($sql);
    }
}
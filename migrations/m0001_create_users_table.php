<?php

use app\core\Migration;

class m0001_create_users_table extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255),
                    password VARCHAR(255)
                )
            ";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS users";
        $this->database->query($sql);
    }
}
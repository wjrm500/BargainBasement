<?php

use app\core\Migration;

class m0008_add_item_name_to_permissions_table extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE permissions ADD COLUMN item_name VARCHAR(255) DEFAULT NULL AFTER `name`";
        $this->database->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE permissions DROP COLUMN item_name";
        $this->database->query($sql);
    }
}
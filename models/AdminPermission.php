<?php

namespace app\models;

use app\core\db\DbModel;

class AdminPermission extends DbModel
{
    public string $name = '';
    public string $href = '';

    public function labels(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [];
    }

    public static function tableName(): string
    {
        return 'admin_permissions';
    }

    public function attributes(): array
    {
        return ['name', 'href'];
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class Permission extends DbModel
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
        return 'permissions';
    }

    public static function attributes(): array
    {
        return ['name', 'href'];
    }
}
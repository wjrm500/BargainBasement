<?php

namespace app\models;

use app\core\db\DbModel;

class Product extends DbModel
{
    public string $name = '';

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
        return 'products';
    }

    public static function attributes(): array
    {
        return ['name', 'image', 'description'];
    }
}
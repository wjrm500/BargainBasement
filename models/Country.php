<?php

namespace app\models;

use app\core\db\DbModel;

class Country extends DbModel
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
        return 'countries';
    }

    public static function attributes(): array
    {
        return ['name'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }
}
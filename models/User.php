<?php

namespace app\models;

use app\core\db\DbModel;

class User extends DbModel
{
    public string $username = '';
    public string $password = '';
    public string $confirmPassword = '';

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
        return 'users';
    }

    public function attributes(): array
    {
        return ['username', 'password'];
    }
}
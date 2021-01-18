<?php

namespace app\models;

use app\core\db\DbModel;

class UserPermission extends DbModel
{
    public string $user_id = '';
    public string $permission_id = '';

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
        return 'users_permissions';
    }

    public static function attributes(): array
    {
        return ['user_id', 'permission_id'];
    }
}
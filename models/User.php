<?php

namespace app\models;

use app\core\db\DbModel;

class User extends DbModel
{
    public string $username = '';
    public string $password = '';
    public int $country_id;

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

    public static function attributes(): array
    {
        return ['username', 'password', 'country_id'];
    }
    
    public function getPermissions()
    {
        $userPermissions = UserPermission::find(['user_id' => $this->id], true);
        $permissions = array_map(
            fn($up) => Permission::find(['id' => $up->permission_id]),
            $userPermissions
        );
        return $permissions;
    }

    public function isAdmin()
    {
        return boolval($this->getPermissions());
    }
}
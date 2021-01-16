<?php

namespace app\models;

use app\core\db\DbModel;

class User extends DbModel
{
    public string $username = '';
    public string $password = '';
    public bool $admin;

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
    
    public function getAdminPermissions()
    {
        $adminUserPermissions = AdminUserPermission::find(['user_id' => $this->id], true);
        $adminPermissions = array_map(
            fn($aup) => AdminPermission::find(['id' => $aup->permission_id]),
            $adminUserPermissions
        );
        return $adminPermissions;
    }

    public function isAdmin()
    {
        return boolval($this->getAdminPermissions());
    }
}
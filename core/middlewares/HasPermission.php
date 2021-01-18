<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\NotAdminUserException;

class HasPermission extends BaseMiddleware
{
    public int $permissionId;

    public function __construct($permissionId)
    {
        $this->permissionId = $permissionId;
        $this->setException(new NotAdminUserException());
    }

    public function execute()
    {
        if (Application::$app->hasUser()) {
            $permissions = Application::$app->getUser()->getPermissions();
            foreach ($permissions as $permission) {
                if ($permission->id === $this->permissionId) {
                    return true;
                }
            }
        }
        return false;
    }
}
<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\NotAdminUserException;

class IsAdminUser extends BaseMiddleware
{
    public function __construct()
    {
        $this->setException(new NotAdminUserException());
    }

    public function execute()
    {
        return Application::$app->hasUser() && Application::$app->getUser()->isAdmin();
    }
}
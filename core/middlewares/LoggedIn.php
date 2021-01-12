<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\NotLoggedInException;

class LoggedIn extends BaseMiddleware
{
    public function __construct()
    {
        $this->setException(new NotLoggedInException());
    }

    public function execute()
    {
        return Application::$app->hasUser();
    }
}
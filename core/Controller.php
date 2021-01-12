<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    protected array $protectedMethods = [];

    public function render($view, $params = [], $layout = 'main')
    {
        return Application::$app->view->render($view, $params, $layout);
    }

    public function registerProtectedMethod(string $method, array $middlewares)
    {
        $this->protectedMethods[] = [
            'method'      => $method,
            'middlewares' => $middlewares
        ];
    }

    public function getProtectedMethods()
    {
        return $this->protectedMethods;
    }

    public function hasProtectedMethods()
    {
        return boolval($this->protectedMethods);
    }
}
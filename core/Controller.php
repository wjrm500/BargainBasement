<?php

namespace app\core;

class Controller
{
    protected string $layout = 'main';
    protected array $protectedMethods = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function renderViewOnly($view, $params = [])
    {
        return Application::$app->view->renderViewOnly($view, $params);
    }

    public function render($view, $params = [])
    {
        return Application::$app->view->render($view, $params, $this->layout);
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
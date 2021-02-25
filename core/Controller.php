<?php

namespace app\core;

use app\consts\ViewConsts;

class Controller
{
    protected string $layout = 'main';
    protected array $protectedMethods = [];

    public function __construct()
    {
        $this->view = Application::$app->view;
        $this->layoutTree = new LayoutTree([ViewConsts::MAIN => LayoutTree::PLACEHOLDER]);
    }

    protected function getDefaultParams()
    {
        return ['app' => Application::$app];
    }

    public function renderViewOnly(String $view, Array $params = [])
    {
        return $this->view->renderViewOnly($view, $params);
    }

    public function render(Array $params = [])
    {
        return $this->view->render($this->layoutTree, array_merge($this->getDefaultParams(), $params));
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
<?php

namespace app\core;

use app\consts\ViewConsts;
use ReflectionClass;

class Controller
{
    protected array $scripts;
    protected View $view;
    protected LayoutTree $layoutTree;
    protected array $protectedMethods = [];

    public function __construct()
    {
        $this->scripts = ['/js/main.js'];
        $this->view = Application::$app->view;
        $this->layoutTree = new LayoutTree([
            ViewConsts::MAIN => [
                ViewConsts::FLASH_MESSAGES,
                ViewConsts::NAVBAR,
                LayoutTree::PLACEHOLDER
            ]
        ]);
    }

    protected function getDefaultParams()
    {
        $title = str_replace('Controller', '', (new ReflectionClass($this))->getShortName());
        return [
            'app'     => Application::$app,
            'scripts' => $this->getScripts(),
            'title'   => $title
        ];
    }

    protected function addScript($script)
    {
        $this->scripts[] =  $script;
    }

    protected function getScripts()
    {
        return $this->scripts;
    }

    public function renderViewOnly(string $view, array $params = [])
    {
        return $this->view->renderViewOnly($view, $params);
    }

    public function render(array $params = [])
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
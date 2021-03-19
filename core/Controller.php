<?php

namespace app\core;

use app\consts\ViewConsts;
use ReflectionClass;

class Controller
{
    protected array $scripts = [];
    protected array $stylesheets = [];
    protected View $view;
    protected LayoutTree $layoutTree;
    protected array $protectedMethods = [];

    public function __construct()
    {
        $this->scripts = ['/js/main.js'];
        $this->app = Application::$app;
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
            'app'         => $this->app,
            'scripts'     => $this->getScripts(),
            'stylesheets' => $this->getStylesheets(),
            'title'       => $title
        ];
    }

    protected function addScript($script)
    {
        $this->scripts[] = $script;
    }

    protected function getScripts()
    {
        return $this->scripts;
    }

    protected function addStylesheet($stylesheet)
    {
        $this->stylesheets[] = $stylesheet;
    }

    protected function getStylesheets()
    {
        return $this->stylesheets;
    }

    public function renderViewOnly(string $view, array $params = [])
    {
        return $this->app->view->renderViewOnly($view, $params);
    }

    public function render(array $params = [])
    {
        return $this->app->view->render($this->layoutTree, array_merge($this->getDefaultParams(), $params));
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
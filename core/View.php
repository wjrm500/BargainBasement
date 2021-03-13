<?php

namespace app\core;

class View
{
    public array $params;

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function render(LayoutTree $layoutTree, array $params = [])
    {
        $this->setParams($params);
        $layoutTree->setView($this);
        $layoutTree->construct();
        return $layoutTree();
    }

    public function renderViewOnly(string $view, array $params = null)
    {
        return $this->getViewContent($view, $params);
    }
    
    public function getViewContent(string $view, array $params = null)
    {
        foreach ($params ?? $this->params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        if (str_ends_with($view, '.html')) {
            require __DIR__ . "/../views/{$view}";
        } else {
            require __DIR__ . "/../views/{$view}.php";
        }
        return ob_get_clean();
    }
}
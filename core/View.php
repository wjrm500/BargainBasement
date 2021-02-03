<?php

namespace app\core;

class View
{
    public function render($view, $params = [], $layout = 'main')
    {
        $view = $this->getViewContent($view, $params);
        if ($layout) {
            $layout = $this->getLayoutContent($layout, $params);
            return str_replace('{{ content }}', $view, $layout);
        }
        return $view;
    }
    
    public function getViewContent($view, $params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        require __DIR__ . "/../views/{$view}.php";
        return ob_get_clean();
    }

    public function getLayoutContent($layout, $params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        require_once __DIR__ . "/../views/layouts/{$layout}.php";
        return ob_get_clean();
    }
}
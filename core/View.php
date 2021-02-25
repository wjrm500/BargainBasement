<?php

namespace app\core;

class View
{
    public Array $params;

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function render(LayoutTree $layoutTree, Array $params = [])
    {
        /* Example $layoutTree below...
        'tree_main' => [
                'tree_nav',
                'tree_shop' => [
                    'tree_products',
                    'tree_basket'
                ]
            ]
        */
        $this->setParams($params);
        $layoutTree->setView($this);
        $layoutTree->construct();
        return $layoutTree();
    }

    public function renderViewOnly(String $view, Array $params = [])
    {
        $this->setParams($params);
        return $this->getViewContent($view, $params);
    }
    
    public function getViewContent($view)
    {
        foreach ($this->params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        require __DIR__ . "/../views/{$view}.php";
        return ob_get_clean();
    }
}
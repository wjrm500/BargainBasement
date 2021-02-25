<?php

namespace app\core;

class View
{
    public Array $params;
    public Array $generatedViews;

    public function __construct()
    {
        $this->params = [];
        $this->generatedViews = [];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function render(Array $layoutTree, Array $params = [])
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
        return $this->generateViewFromLayoutTree($layoutTree);
    }

    public function generateViewFromLayoutTree($layoutTree)
    {
        // Each node on a layoutTree must be unique - I should create a LayoutTree class
        // Recurse through the layoutTree, starting at the leaf nodes, rendering view content into layout files and storing this for reuse
        // Now need to convert all views to use this
        $views = $layoutTree[array_key_first($layoutTree)];
        $pass = count(array_filter(array_keys($views), fn($x) => is_int($x))) === count($views);
        if (!$pass) {
            foreach ($views as $index => $nodes) {
                if (!is_int($index)) $this->generateViewFromLayoutTree([$index => $nodes]);
            }
        }
        $layout = $this->getViewContent(0, array_key_first($layoutTree), $this->params);
        foreach ($views as $index => $view) {
            $view = $this->getViewContent($index, $view, $this->params);
            $layout = preg_replace('{{ content }}', $view, $layout, 1);
        }
        $this->generatedViews[array_key_first($layoutTree)] = $layout;
        return $layout;
    }

    public function renderViewOnly($view, $params = [])
    {
        return $this->getViewContent($view, $params);
    }
    
    private function getViewContent($index, $view, $params = [])
    {
        if (!empty($this->generatedViews[$index])) {
            return $this->generatedViews[$index];
        }
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        require __DIR__ . "/../views/{$view}.php";
        return ob_get_clean();
    }
}
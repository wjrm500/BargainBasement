<?php

namespace app\core;

class LayoutTree
{
    public Array $constructedViews;
    private Array $tree;
    private View $view;

    public function __construct(Array $tree)
    {
        if ($this->validateTree($tree)) $this->tree = $tree;
        $this->treeIndex = array_key_first($this->tree);
    }

    public function __invoke()
    {
        return $this->constructedViews[$this->treeIndex];
    }

    public function setView(View $view)
    {
        $this->view = $view;
    }

    public function construct(Array $tree = [])
    {
        if (empty($tree)) $tree = $this->tree;
        $views = $tree[array_key_first($tree)];
        $pass = count(array_filter(array_keys($views), fn($x) => is_int($x))) === count($views);
        if (!$pass) {
            foreach ($views as $index => $nodes) {
                if (!is_int($index)) $this->construct([$index => $nodes]);
            }
        }
        $constructedView = $this->view->getViewContent(array_key_first($tree));
        foreach ($views as $index => $view) {
            if ($this->hasConstructedView($index)) {
                $constructedView = preg_replace('/{{ content }}/', $this->getConstructedView($index), $constructedView, 1);
            } else {
                $view = $this->view->getViewContent($view);
                $constructedView = preg_replace('/{{ content }}/', $view, $constructedView, 1);
            }
        }
        $this->addConstructedView(array_key_first($tree), $constructedView);
    }

    private function validateTree(Array $tree)
    {
        if (count(array_keys($tree)) !== 1) {
            return false;
        }
        return true;
    }

    private function addConstructedView(String $key, String $value)
    {
        $this->constructedViews[$key] = $value;
    }

    private function getConstructedView(String $key)
    {
        return $this->constructedViews[$key];
    }

    private function hasConstructedView(String $key)
    {
        return !empty($this->constructedViews[$key]);
    }
}
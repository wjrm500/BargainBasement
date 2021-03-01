<?php

namespace app\core;

use app\core\utilities\ArrayUtils;

class LayoutTree
{
    public const PLACEHOLDER = ':placeholder';

    public Array $constructedViews;
    private Array $tree;
    private View $view;

    public function __construct(Array $tree = [])
    {
        if ($this->validateTree($tree)) $this->tree = $tree;
        $this->treeIndex = array_key_first($this->tree);
    }

    public function customise($replacement)
    {
        // Recurse through array until find placeholder, then replace
        // Setting function to variable so it can be unset later to prevent redeclaration error
        $recurseThroughTree = function(&$array, $replacement) use (&$recurseThroughTree) {
            foreach ($array as $key => &$value)  {
                if (is_array($value)) {
                    $recurseThroughTree($value, $replacement);
                } else {
                    if ($value === LayoutTree::PLACEHOLDER) {
                        if (is_array($replacement) && ArrayUtils::isAssoc($replacement)) {
                            $array = ArrayUtils::changeKey($array, $key, array_key_first($replacement));
                            $array[array_key_first($replacement)] = reset($replacement);
                        } else {
                            $value = $replacement;
                        }
                        return;
                    }
                }
            }
            return 'No placeholders found';
        };
        $recurseThroughTree($this->tree, $replacement);
        unset($recurseThroughTree);
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
            // Need to count num instances of {{ content }} and make sure this matches the number of views
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
        // All key-value pairs must be of form int => string or string => array
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
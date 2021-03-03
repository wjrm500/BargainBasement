<?php

namespace app\core;

use app\core\exceptions\LayoutTreeContentMissingException;
use app\core\utilities\ArrayUtils;
use app\utils\ArrayUtils as UtilsArrayUtils;

class LayoutTree
{
    public const PLACEHOLDER = ':placeholder';

    public array $constructedViews;
    private array $tree;
    private View $view;

    public function __construct(array $tree = [])
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

    public function construct(array $tree = [])
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
        if (count($views) !== substr_count($constructedView, '{{ content }}')) {
            throw new LayoutTreeContentMissingException;
        }
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

    private function validateTree(array $tree)
    {
        // There must be a single base layout view
        if (count(array_keys($tree)) !== 1) {
            return false;
        }

        // All key-value pairs must be of form int => string or string => array
        $keyValuePairs = ArrayUtils::getAllKeyValuePairsFromNestedArray($tree);
        $validatedKeyValuePairs = array_filter($keyValuePairs, function($pair) {
            [$key, $value] = $pair;
            return (is_int($key) && is_string($value)) || (is_string($key) && is_array($value));
        });
        if (count($keyValuePairs) !== count($validatedKeyValuePairs)) {
            return false;
        }

        return true;
    }

    private function addConstructedView(string $key, string $value)
    {
        $this->constructedViews[$key] = $value;
    }

    private function getConstructedView(string $key)
    {
        return $this->constructedViews[$key];
    }

    private function hasConstructedView(string $key)
    {
        return !empty($this->constructedViews[$key]);
    }
}
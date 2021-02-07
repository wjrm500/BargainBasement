<?php

namespace app\core;

class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    public function getBody()
    {
        if ($this->isGet()) {
            return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if ($this->isPost()) {
            $filesInput = array_combine(array_keys($_FILES), array_column($_FILES, 'name'));
            return array_merge(
                filter_var_array($filesInput, FILTER_SANITIZE_SPECIAL_CHARS),
                filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)
            );
        }
    }

    private function getExplodedPath()
    {
        return explode('/', $_SERVER['REQUEST_URI']);
    }

    public function getPathElementCount()
    {
        return count($this->getExplodedPath());
    }

    public function getSlug()
    {
        $explodedPath = $this->getExplodedPath();
        return $explodedPath[array_key_last($explodedPath)];
    }

    public function getFile($key)
    {
        return $_FILES[$key];
    }
}
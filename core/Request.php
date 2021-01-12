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
            return filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }
}
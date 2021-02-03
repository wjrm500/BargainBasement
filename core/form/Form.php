<?php

namespace app\core\form;

use app\core\factory\FieldFactory;

class Form {
    public $model;
    public string $action;
    public string $method;

    public function __construct($model, $action = null, $method = null)
    {
        $this->model = $model;
        $this->action = $action ?? $_SERVER['REQUEST_URI'];
        $this->method = $method ?? 'post';
        $this->fieldFactory = new FieldFactory();
    }

    public function begin()
    {
        return sprintf(
            '<form action="%s" method="%s">',
            $this->action,
            $this->method
        );
    }

    public function end()
    {
        return '</form>';
    }

    public function field($attribute)
    {
        return $this->fieldFactory->make($this->model, $attribute);
    }
}
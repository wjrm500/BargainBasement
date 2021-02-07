<?php

namespace app\core\form;

use app\core\Application;
use app\core\Model;

abstract class Field
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_FILE = 'file';
    public const TYPE_NUMBER = 'number';

    public Model $model;
    public string $attribute;
    public array $extraProperties;
    public string $type;

    public function __construct($model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->extraProperties = [];
    }

    protected function setType($type)
    {
        $this->type = $type;
    }

    abstract public function renderInput();

    public function __toString()
    {
        return Application::$app->view->renderViewOnly(
            'partials/form/field',
            [
                'label'         => $this->model->getLabel($this->attribute),
                'input'         => $this->renderInput(),
                'errorMessage'  => $this->model->getFirstError($this->attribute)
            ]
        );
    }

    protected function addExtraProperty($key, $value)
    {
        $this->extraProperties[$key] = $value;
    }
}
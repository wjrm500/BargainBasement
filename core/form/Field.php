<?php

namespace app\core\form;

use app\core\Application;
use app\core\Model;

abstract class Field
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_FILE = 'file';

    public Model $model;
    public string $attribute;
    public string $type;

    public function __construct($model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->view = Application::$app->view;
    }

    protected function setType($type)
    {
        $this->type = $type;
    }

    abstract public function renderInput();

    public function __toString()
    {
        return $this->view->render(
            'partials/form/field',
            [
                'label'         => $this->model->getLabel($this->attribute),
                'input'         => $this->renderInput(),
                'errorMessage'  => $this->model->getFirstError($this->attribute)
            ],
            null
        );
    }
}
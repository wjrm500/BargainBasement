<?php

namespace app\core\form;

use app\core\Application;

abstract class InputField extends Field
{
    public const VIEW_PATH = 'partials/form/input_field';

    private function getInvalidText()
    {
        return $this->model->hasError($this->attribute) ? 'is-invalid' : '';
    }

    public function renderInput()
    {
        return Application::$app->view->renderViewOnly(
            static::VIEW_PATH,
            [
                'extraProperties' => $this->extraProperties,
                'isInvalid'       => $this->getInvalidText(),
                'name'            => $this->attribute,
                'type'            => $this->type,
                'value'           => !is_array($this->model->{$this->attribute}) ? $this->model->{$this->attribute} : null
            ]
        );
    }
}
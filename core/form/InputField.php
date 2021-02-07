<?php

namespace app\core\form;

abstract class InputField extends Field
{
    public const VIEW_PATH = 'partials/form/input_field';

    private function getInvalidText()
    {
        return $this->model->hasError($this->attribute) ? 'is-invalid' : '';
    }

    public function renderInput()
    {
        return $this->view->render(
            static::VIEW_PATH,
            [
                'field'     => $this,
                'type'      => $this->type,
                'isInvalid' => $this->getInvalidText(),
                'name'      => $this->attribute,
                'value'     => $this->model->{$this->attribute}
            ],
            null
        );
    }
}
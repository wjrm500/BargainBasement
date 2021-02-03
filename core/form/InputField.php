<?php

namespace app\core\form;

abstract class InputField extends Field
{
    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
    }

    public function renderInput()
    {
        return $this->view->render(
            'partials/form/input_field',
            [
                'type' => $this->type
            ],
            null
        );
    }
}
<?php

namespace app\core\form;

class TextareaField extends Field
{
    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_TEXTAREA);
    }

    public function renderInput()
    {
        return $this->view->render(
            'partials/form/textarea_field',
            [
                'type' => $this->type,
                'name' => $this->attribute
            ],
            null
        );
    }
}
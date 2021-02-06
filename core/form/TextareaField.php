<?php

namespace app\core\form;

class TextareaField extends InputField
{
    public const VIEW_PATH = 'partials/form/textarea_field';

    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_TEXTAREA);
    }
}
<?php

namespace app\core\form;

class FloatInputField extends InputField
{
    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_NUMBER);
        $this->addExtraProperty('step', '0.01');
    }
}
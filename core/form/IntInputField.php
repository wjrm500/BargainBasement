<?php

namespace app\core\form;

class IntInputField extends InputField
{
    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_NUMBER);
    }
}
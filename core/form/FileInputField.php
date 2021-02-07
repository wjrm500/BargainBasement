<?php

namespace app\core\form;

class FileInputField extends InputField
{
    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_FILE);
        $this->addExtraProperty('access', 'image/jpeg, image/png');
    }
}
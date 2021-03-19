<?php

namespace app\core\form;

use app\models\Product;

class SelectField extends InputField
{
    public const VIEW_PATH = 'partials/form/select_field';

    public function __construct($model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->setType(static::TYPE_SELECT);
        $this->addExtraProperty('multiple', 'multiple');
        $this->addExtraProperty('options', $model->optionsForAttribute($attribute));
    }
}
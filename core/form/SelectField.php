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
        $this->addExtraProperty('options', array_combine(Product::findAllFetchColumn(0), Product::findAllFetchColumn(1))); // TODO: Make generic - but how do we pass in options?
    }
}
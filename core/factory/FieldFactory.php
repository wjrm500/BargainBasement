<?php

namespace app\core\factory;

use app\core\Application;
use app\core\form\Field;
use app\core\form\TextareaField;
use app\core\form\TextInputField;

class FieldFactory
{
    public Field $field;

    public function make($model, $attribute)
    {
        $dataType = Application::$app->database->getFieldType($model->tableName(), $attribute);
        switch ($dataType) {
            case 'varchar':
                return new TextInputField($model, $attribute);
            case 'text':
                return new TextareaField($model, $attribute);
        }
    }
}
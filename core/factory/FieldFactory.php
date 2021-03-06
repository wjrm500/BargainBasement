<?php

namespace app\core\factory;

use app\core\Application;
use app\core\form\Field;
use app\core\form\FileInputField;
use app\core\form\FloatInputField;
use app\core\form\IntInputField;
use app\core\form\SelectField;
use app\core\form\TextareaField;
use app\core\form\TextInputField;

class FieldFactory
{
    public Field $field;
    public array $fieldsForCustomInputTypes;
    public array $fieldsForDefaultDataTypes;

    public function __construct()
    {
        $this->fieldsForCustomInputTypes = [
            'file'   => FileInputField::class,
            'select' => SelectField::class
        ];
        $this->fieldsForDefaultDataTypes = [
            'float'   => FloatInputField::class,
            'int'     => IntInputField::class,
            'text'    => TextareaField::class,
            'varchar' => TextInputField::class
        ];
    }

    public function make($model, $attribute)
    {
        if ($model->hasCustomInputType($attribute)) {
            $inputType = $model->attributeCustomInputTypes()[$attribute];
            $field = $this->getFieldForCustomInputType($inputType);
        } else {
            $dataType = Application::$app->database->getFieldType($model->tableName(), $attribute);
            $field = $this->getFieldForDefaultDataType($dataType);
        }
        return new $field($model, $attribute);
    }

    private function getFieldForCustomInputType($inputType)
    {
        return $this->fieldsForCustomInputTypes[$inputType];
    }

    private function getFieldForDefaultDataType($dataType)
    {
        if ($dataType === 'text') {
            $a = 1;
        }
        return $this->fieldsForDefaultDataTypes[$dataType];
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class Product extends DbModel
{
    public string $name = '';
    public string $image = '';
    public string $description = '';

    public function labels(): array
    {
        return [
            'name'        => 'Name',
            'image'       => 'Image',
            'description' => 'Description'
        ];
    }

    public function rules(): array
    {
        return [
            'name'        => [self::RULE_REQUIRED],
            'image'       => [
                self::RULE_REQUIRED,
                [
                    self::RULE_IMAGE_MAX_SIZE,
                    'maxSize' => 1000000
                ],
                [
                    self::RULE_IMAGE_SQUARE,
                    'height' => 1000
                ]
            ],
            'description' => [self::RULE_REQUIRED]
        ];
    }

    public static function tableName(): string
    {
        return 'products';
    }

    public static function attributes(): array
    {
        return ['name', 'image', 'description'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [
            'image' => 'file'
        ];
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class Product extends DbModel
{
    public string $name = '';
    public string $image = '';
    public string $description = '';
    public float $price = 0.00;
    public int $weight = 0;

    public function labels(): array
    {
        return [
            'name'        => 'Name',
            'image'       => 'Image',
            'description' => 'Description',
            'price'       => 'Price (£)',
            'weight'      => 'Weight (g)'
        ];
    }

    public function rules(): array
    {
        return [
            'name'            => [self::RULE_REQUIRED],
            'image'           => [
                self::RULE_REQUIRED,
                [
                    self::RULE_IMAGE_MAX_SIZE,
                    'maxSize' => 1000000
                ],
                [
                    self::RULE_IMAGE_SQUARE,
                    'height'  => 200
                ]
            ],
            'description'     => [self::RULE_REQUIRED],
            'price'           => [self::RULE_REQUIRED],
            'weight'          => [self::RULE_REQUIRED]
        ];
    }

    public static function tableName(): string
    {
        return 'products';
    }

    public static function attributes(): array
    {
        return ['name', 'image', 'description', 'price', 'weight'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [
            'image' => 'file'
        ];
    }
}
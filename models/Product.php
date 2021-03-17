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

    public int $energy_kcal = 0;
    public float $fat_g = 0.00;
    public float $saturates_g = 0.00;
    public float $sugars_g = 0.00;
    public float $salt_g = 0.00;

    public function labels(): array
    {
        return [
            'name'        => 'Name',
            'image'       => 'Image',
            'description' => 'Description',
            'price'       => 'Price (£)',
            'weight'      => 'Weight (g)',
            'energy_kcal' => 'Energy (kcal)',
            'fat_g'       => 'Fat (g)',
            'saturates_g' => 'Saturates (g)',
            'sugars_g'    => 'Sugars (g)',
            'salt_g'      => 'Salt (g)'
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
            'weight'          => [self::RULE_REQUIRED],
            'energy_kcal'           => [self::RULE_REQUIRED],
            'fat_g'           => [self::RULE_REQUIRED],
            'saturates_g'           => [self::RULE_REQUIRED],
            'sugars_g'           => [self::RULE_REQUIRED],
            'salt_g'           => [self::RULE_REQUIRED],
        ];
    }

    public static function tableName(): string
    {
        return 'products';
    }

    public static function attributes(): array
    {
        return ['name', 'image', 'description', 'price', 'weight', 'energy_kcal', 'fat_g', 'saturates_g', 'sugars_g', 'salt_g'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [
            'image' => 'file'
        ];
    }

    public function getPricePerKg()
    {
        return $this->price / $this->weight * 1000;
    }
}
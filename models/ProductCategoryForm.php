<?php

namespace app\models;

class ProductCategoryForm extends ProductCategory
{
    public array $products = [];

    public function __construct()
    {
        $this->products = Product::findAll();
    }

    public static function attributes(): array
    {
        return array_merge(
            parent::attributes(),
            ['products']
        );
    }

    public function labels(): array
    {
        return [
            'products'        => 'Products',
        ];
    }

    public function rules(): array
    {
        return [
                'products' => [self::RULE_REQUIRED]
        ];
    }

    public function save($returnLastInsertId = true)
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        unset($this->confirmPassword);
        return $this->id = parent::save($returnLastInsertId);
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class ShoppingCartItem extends DbModel
{
    public int $shopping_cart_id = 0;
    public int $product_id = 0;
    public int $quantity = 0;

    public function labels(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [];
    }

    public static function tableName(): string
    {
        return 'shopping_cart_items';
    }

    public static function attributes(): array
    {
        return ['shopping_cart_id', 'product_id', 'quantity'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }
}
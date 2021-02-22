<?php

namespace app\models;

use app\core\db\DbModel;

class ShoppingCart extends DbModel
{
    public int $user_id = 0;
    public string $created_at = '';
    public string $updated_at = '';
    public int $paid = 0;

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
        return 'shopping_carts';
    }

    public static function attributes(): array
    {
        return ['user_id', 'created_at', 'updated_at', 'paid'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }

    public function getItems()
    {
        return ShoppingCartItem::find(['shopping_cart_id' => $this->id], true);
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class ShoppingCart extends DbModel
{
    public int $user_id = 0;
    public string $created_at = '';
    public string $updated_at = '';

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
        return ['user_id', 'created_at', 'updated_at'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }

    public function getItems()
    {
        return ShoppingCartItem::find(['shopping_cart_id' => $this->id], true);
    }

    public function getNumItems()
    {
        return count($this->getItems());
    }

    public function payment()
    {
        return Payment::find(['shopping_cart_id'=> $this->id]);
    }

    public function paid()
    {
        return boolval($this->payment());
    }

    public static function findCurrent($userId)
    {
        $carts = self::find(['user_id' => $userId], true);
        $unpaidCarts = array_filter($carts, fn($cart) => !$cart->paid());
        return reset($unpaidCarts);
    }

    public function getOverallPrice()
    {
        return $this->paid() ? $this->payment()->price_paid : array_reduce($this->getItems(), fn($carry, $item) => $carry + (float) $item->totalPrice(), 0);
    }
}
<?php

namespace app\models;

use app\core\db\DbModel;

class Payment extends DbModel
{
    public int $shopping_cart_id = 0;
    public string $payment_made_at = '';
    public float $price_paid = 0;
    
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
        return 'payments';
    }

    public static function attributes(): array
    {
        return ['shopping_cart_id', 'payment_made_at', 'price_paid'];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }
}
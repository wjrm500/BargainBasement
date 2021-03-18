<?php

namespace app\models;

use app\core\db\DbModel;

class ProductCategory extends DbModel
{
    public string $name = '';

    public static function tableName(): string
    {
        return 'product_categories';
    }

    public static function attributes(): array
    {
        return ['name'];
    }

    public function labels(): array
    {
        return [
            'name' => 'Name'
        ];
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
        ];
    }

    public function attributeCustomInputTypes(): array
    {
        return [];
    }

    public function products()
    {
        $statement = $this->pdo->prepare('SELECT product_id FROM products_categories WHERE category_id = :category_id');
        $statement->bindParam(':category_id', $this->id);
        $statement->execute();
        $productIds = array_column($statement->fetchAll(), 'product_id');
        return array_map(fn($productId) => Product::find($productId), $productIds);
    }
}
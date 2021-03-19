<?php

namespace app\models;

class ProductCategoryForm extends ProductCategory
{
    public string $name = '';
    public ?array $products = [];

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
            'name'     => 'Name',
            'products' => 'Products',
        ];
    }

    public function rules(): array
    {
        return [
            'name'     => [self::RULE_REQUIRED],
            'products' => [self::RULE_REQUIRED]
        ];
    }

    public function attributeCustomInputTypes(): array
    {
        return [
            'products' => 'select'
        ];
    }

    public function save($returnLastInsertId = true)
    {
        $productIds = $this->products;
        unset($this->products);
        $this->id = parent::save($returnLastInsertId);
        foreach ($productIds as $productId) {
            $sql = "INSERT INTO products_product_categories (product_id, category_id) VALUES (:productId, :categoryId)";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':productId', $productId);
            $statement->bindParam(':categoryId', $this->id);
            $statement->execute();
        }
        return true;
    }

    public function optionsForAttribute()
    {
        return array_combine(Product::findAllFetchColumn(0), Product::findAllFetchColumn(1));
    }
}
<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::findAll();
        $productWidgets = array_map(
            function($product) {
                return $this->renderViewOnly(
                    'partials/shop/product_widget',
                    ['product' => $product]
                );
            },
            $products
        );
        return $this->render(
            'shop',
            ['productWidgets' => $productWidgets]
        );
    }
}
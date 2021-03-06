<?php

namespace app\controllers;

use app\consts\ViewConsts;
use app\core\Application;
use app\core\Controller;
use app\core\LayoutTree;
use app\core\Request;
use app\core\Response;
use app\models\Product;
use app\models\ShoppingCart;
use app\models\ShoppingCartItem;

class ShopController extends Controller
{
    public function index()
    {
        $this->addScript('/js/shopping.js');
        $products = Product::findAll();
        $productWidgets = array_map(
            function($product) {
                return $this->renderViewOnly(ViewConsts::SHOP_PROD_WIDGET, compact('product'));
            },
            $products
        );
        $this->layoutTree->customise([ViewConsts::SHOP => [ViewConsts::SHOP_PRODS, ViewConsts::SHOP_BASKET]]);
        return $this->render(compact('productWidgets'));
    }

    public function getBasketData()
    {
        $app = Application::$app;
        if ($app->hasUser()) {
            $userId = $app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            if ($shoppingCart) {
                $shoppingCartItems = $shoppingCart->getItems();
                $basketData = [];
                foreach ($shoppingCartItems as $shoppingCartItem) {
                    $basketData[$shoppingCartItem->product_id] = $shoppingCartItem->quantity;
                }
                return json_encode($basketData);
            }
        }
    }

    public function getDetailedBasketData()
    {
        $app = Application::$app;
        if ($app->hasUser()) {
            $userId = $app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            if ($shoppingCart) {
                $shoppingCartItems = $shoppingCart->getItems();
                $basketData = [];
                foreach ($shoppingCartItems as $shoppingCartItem) {
                    $basketData[$shoppingCartItem->product_id] = [
                        'Name'        => $shoppingCartItem->name(),
                        'Price'       => $shoppingCartItem->price(true),
                        'Quantity'    => $shoppingCartItem->quantity,
                        'Total Price' => $shoppingCartItem->totalPrice(true)
                    ];
                }
                return json_encode($basketData);
            }
        }
    }

    public function postDetailedBasketData()
    {
        $app = Application::$app;
        $localBasketData = $app->request->getBody();
        $basketData = [];
        foreach ($localBasketData['localShoppingCart'] as $productId => $quantity) {
            $product = Product::find(['id' => $productId]);
            $basketData[$productId] = [
                'Name'        => $product->name,
                'Price'       => '£' . (string) number_format($product->price, 2, '.', ''),
                'Quantity'    => $quantity,
                'Total Price' => '£' . (string) number_format($product->price * $quantity, 2, '.', '')
            ];
        }
        return json_encode($basketData);
    }

    public function persistBasket()
    {
        // Can some of this stuff be moved into the Model?
        $app = Application::$app;
        $basketData = $app->request->getBody()['basketData'];
        if ($app->hasUser()) {
            $userId = $app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            if ($shoppingCart) {
                $shoppingCart->bindData([
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $shoppingCart->update();

                $existingCartItems = $shoppingCart->getItems();
                foreach ($existingCartItems as $existingCartItem) {
                    $productId = $existingCartItem->product_id;
                    if (array_key_exists($productId, $basketData)) {
                        $existingCartItem->bindData([
                            'quantity' => (int) $basketData[$productId]
                        ]);
                        $existingCartItem->update();
                    } else {
                        $existingCartItem->delete();
                    }
                }
                $newCartItems = array_diff_key(
                    $basketData,
                    array_flip(
                        array_column(
                            $existingCartItems,
                            'product_id'
                        )
                    )
                );
                foreach ($newCartItems as $productId => $quantity) {
                    $shoppingCartItem = new ShoppingCartItem();
                    $shoppingCartItem->bindData([
                        'shopping_cart_id' => $shoppingCart->id,
                        'product_id'       => $productId,
                        'quantity'         => $quantity
                    ]);
                    $shoppingCartItem->save();
                }
            } else {
                $shoppingCart = new ShoppingCart();
                $shoppingCart->bindData([
                    'user_id'    => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'paid'       => 0
                ]);
                $shoppingCartId = $shoppingCart->save(true);
                foreach ($basketData as $productId => $quantity) {
                    $shoppingCartItem = new ShoppingCartItem();
                    $shoppingCartItem->bindData([
                        'shopping_cart_id' => $shoppingCartId,
                        'product_id'       => $productId,
                        'quantity'         => $quantity
                    ]);
                    $shoppingCartItem->save();
                }
            }
        }
    }

    public function checkout(Request $request, Response $response)
    {
        $this->addScript('/js/checkout.js');
        $app = Application::$app;
        if ($app->hasUser()) {
            $userId = $app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            $shoppingCartExists = $shoppingCart && $shoppingCart->getNumItems();
            $this->layoutTree->customise(ViewConsts::CHECKOUT);
            return $this->render(['shoppingCartExists' => $shoppingCartExists]);
        }
        return $response->redirect('/login', '/shop/checkout');
    }
}
<?php

namespace app\controllers;

use app\consts\BootstrapColorConsts;
use app\consts\ViewConsts;
use app\core\Application;
use app\core\Controller;
use app\core\Csrf;
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
        $productData = [];
        foreach ($products as $product) {
            $productData[$product->id] = [
                'image'       => $product->image,
                'name'        => str_replace(' ', '-', $product->name),
                'price'       => '£' . (string) number_format($product->price, 2, '.', ''),
                'quantity'    => 1,
                'totalPrice'  => '£' . (string) number_format($product->price, 2, '.', '')
            ];
        }
        $productWidgets = array_map(
            fn($product) => $this->renderViewOnly(ViewConsts::SHOP_PROD_WIDGET, compact('product')),
            $products
        );
        $this->layoutTree->customise([ViewConsts::SHOP => [ViewConsts::SHOP_PRODS, ViewConsts::SHOP_BASKET]]);
        return $this->render([
            'productData'    => json_encode($productData),
            'productWidgets' => $productWidgets
        ]);
    }

    public function getBasketData()
    {
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            if ($shoppingCart) {
                $shoppingCartItems = $shoppingCart->getItems();
                $basketData = [];
                foreach ($shoppingCartItems as $shoppingCartItem) {
                    $basketData[$shoppingCartItem->product_id] = [
                        'image'       => $shoppingCartItem->image(),
                        'name'        => $shoppingCartItem->name(),
                        'price'       => $shoppingCartItem->price(true),
                        'quantity'    => $shoppingCartItem->quantity,
                        'totalPrice'  => $shoppingCartItem->totalPrice(true)
                    ];
                }
                return json_encode($basketData);
            }
        }
    }

    public function postBasketData()
    {
        $localBasketData = $this->app->request->getBody();
        $basketData = [];
        foreach ($localBasketData['localShoppingCart'] as $productId => $quantity) {
            $product = Product::find(['id' => $productId]);
            $basketData[$productId] = [
                'image'       => $product->image,
                'name'        => $product->name,
                'price'       => '£' . (string) number_format($product->price, 2, '.', ''),
                'quantity'    => $quantity,
                'totalPrice'  => '£' . (string) number_format($product->price * $quantity, 2, '.', '')
            ];
        }
        return json_encode($basketData);
    }

    public function persistBasket()
    {
        // Can some of this stuff be moved into the Model?
        $basketData = $this->app->request->getBody()['basketData'];
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
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

    public function getCheckout(Request $request, Response $response)
    {
        $this->addScript('/js/checkout.js');
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
            $shoppingCart = ShoppingCart::find(['user_id' => $userId]);
            $this->layoutTree->customise(ViewConsts::CHECKOUT);
            return $this->render([
                'csrfTokenName'      => Csrf::TOKEN_NAME,
                'csrfTokenValue'     => (new Csrf())->getToken(),
                'shoppingCartExists' => $shoppingCart && $shoppingCart->getNumItems()
            ]);
        }
        return $response->redirect('/login', '/shop/checkout');
    }

    public function postCheckout(Request $request, Response $response)
    {
        (new Csrf())->checkToken();
        $this->app->session->setFlashMessage('Your order has successfully been placed!', BootstrapColorConsts::SUCCESS);
        return $response->redirect('/');
    }
}
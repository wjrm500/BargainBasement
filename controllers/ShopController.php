<?php

namespace app\controllers;

use app\consts\BootstrapColorConsts;
use app\consts\ViewConsts;
use app\core\Controller;
use app\core\Csrf;
use app\core\Request;
use app\core\Response;
use app\models\Payment;
use app\models\Product;
use app\models\ProductCategory;
use app\models\ShoppingCart;
use app\models\ShoppingCartItem;

class ShopController extends Controller
{
    public function index()
    {
        $this->addScript('/js/shopping.js');
        $this->addScript('/js/search_shop.js');
        $this->addScript('/js/product_modal.js');
        $products = Product::findAll();
        $productData = [];
        foreach ($products as $product) {
            $productData[$product->id] = [
                'description' => str_replace(' ', '_', $product->description),
                'image'       => $product->image,
                'name'        => str_replace(' ', '_', $product->name),
                'price'       => '£' . (string) number_format($product->price, 2, '.', ''),
                'weight'      => $product->weight,
                'nutrition'   => [
                    'energy'    => $product->energy_kcal,
                    'fat'       => $product->fat_g,
                    'saturates' => $product->saturates_g,
                    'sugars'    => $product->sugars_g,
                    'salt'      => $product->salt_g
                ],
                'categories'  => $product->categories()
            ];
        }
        $productWidgets = array_map(
            fn($product) => $this->renderViewOnly(ViewConsts::SHOP_PROD_WIDGET, compact('product')),
            $products
        );
        $this->layoutTree->customise([
            ViewConsts::SHOP => [
                ViewConsts::SHOP_SEARCH,
                ViewConsts::SHOP_MINI_BASKET,
                ViewConsts::SHOP_PRODS,
                ViewConsts::SHOP_BASKET
            ]
        ]);
        return $this->render([
            'productCategories' => ProductCategory::findAll(),
            'productData'    => json_encode($productData),
            'productWidgets' => $productWidgets
        ]);
    }

    public function getBasketData()
    {
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
            $shoppingCart = ShoppingCart::findCurrent($userId);
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
                
            }
        }
        return json_encode($basketData ?? []);
    }

    public function postBasketData()
    {
        // Get correct product information from DB
        $localBasketData = $this->app->request->getBody();
        $basketData = [];
        foreach ($localBasketData['localShoppingCart'] as $productId => $itemData) {
            $product = Product::find(['id' => $productId]);
            $basketData[$productId] = [
                'image'       => $product->image,
                'name'        => $product->name,
                'price'       => '£' . (string) number_format($product->price, 2, '.', ''),
                'quantity'    => $itemData['quantity'],
                'totalPrice'  => '£' . (string) number_format($product->price * $itemData['quantity'], 2, '.', '')
            ];
        }
        return json_encode($basketData ?? []);
    }

    public function getTemplates()
    {
        $templates = [
            'basketWidgetTemplate' => $this->renderViewOnly(ViewConsts::BASKET_WIDGET_HTML),
            'productModalTemplate' => $this->renderViewOnly(ViewConsts::PRODUCT_MODAL_HTML)
        ];
        return json_encode($templates);
    }

    public function persistBasket()
    {
        // Can some of this stuff be moved into the Model?
        $basketData = $this->app->request->getBody()['basketData'];
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
            $shoppingCart = ShoppingCart::findCurrent($userId);
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
                            'quantity' => (int) $basketData[$productId]['quantity']
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
                foreach ($newCartItems as $productId => $itemData) {
                    $shoppingCartItem = new ShoppingCartItem();
                    $shoppingCartItem->bindData([
                        'shopping_cart_id' => $shoppingCart->id,
                        'product_id'       => $productId,
                        'quantity'         => $itemData['quantity']
                    ]);
                    $shoppingCartItem->save();
                }
            } else {
                $shoppingCart = new ShoppingCart();
                $shoppingCart->bindData([
                    'user_id'    => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $shoppingCartId = $shoppingCart->save(true);
                foreach ($basketData as $productId => $itemData) {
                    $shoppingCartItem = new ShoppingCartItem();
                    $shoppingCartItem->bindData([
                        'shopping_cart_id' => $shoppingCartId,
                        'product_id'       => $productId,
                        'quantity'         => $itemData['quantity']
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
            $shoppingCart = ShoppingCart::findCurrent($userId);
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
        if ($this->app->hasUser()) {
            $userId = $this->app->getUser()->id;
            $shoppingCart = ShoppingCart::findCurrent($userId);
            $payment = new Payment();
            $payment->bindData([
                'shopping_cart_id' => $shoppingCart->id,
                'payment_made_at'  => date('Y-m-d H:i:s'),
                'price_paid'       => $shoppingCart->getOverallPrice()
            ]);
            if ($payment->save()) {
                $this->app->session->setFlashMessage('Your order has successfully been placed!', BootstrapColorConsts::SUCCESS);
                return $response->redirect('/');
            }   
        }
    }
}
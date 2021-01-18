<?php

namespace app\controllers\admin;

use app\core\Request;
use app\core\Response;
use app\models\Permission;
use app\models\Product;

class AdminProductController extends AdminController
{
    public const PERMISSION_NAME = 'Products';

    public function __construct()
    {
        $this->permissionId = Permission::find(['name' => self::PERMISSION_NAME])->id;
        parent::__construct();
    }

    public function index()
    {
        $productAttributes = Product::attributes();
        $products = Product::findAll();
        return $this->render('admin/product/home', compact('productAttributes', 'products'));
    }

    public function editProduct(Request $request, Response $response, $productId)
    {
        echo 'Hello';
    }
}
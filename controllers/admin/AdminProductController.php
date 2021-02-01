<?php

namespace app\controllers\admin;

use app\core\Application;
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
        $this->setModel(Product::class);
        parent::__construct();
    }

    public function index()
    {
        return $this->render('admin/item_home');
    }

    public function addProduct(Request $request, Response $response)
    {
        return $this->render('admin/item_add_form');
    }

    public function editProduct(Request $request, Response $response, $productId)
    {
        echo 'Hello';
    }
}
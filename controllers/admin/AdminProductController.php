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
        parent::__construct();
    }

    public function index()
    {
        $productAttributes = Product::attributes();
        $products = Product::findAll();
        $permissions = Application::$app->getUser()->getPermissions();
        return $this->render(
            'admin/permission_home',
            [
                'itemAttributes' => $productAttributes,
                'items'          => $products,
                'permissions'    => $permissions
            ]
        );
    }

    public function editProduct(Request $request, Response $response, $productId)
    {
        echo 'Hello';
    }
}
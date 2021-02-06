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
        $this->permission = Permission::find(['name' => self::PERMISSION_NAME]);
        $this->setModel(Product::class);
        parent::__construct();
    }

    public function index()
    {
        return $this->render('admin/item_home');
    }

    public function addProduct(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $this->model->bindData($request->getBody());
            if ($this->model->validate() && $this->model->save()) {
                Application::$app->session->setFlashMessage(
                    "You successfully added {$this->model->name} to the {$this->model->tableName()} table!",
                    'success'
                );
                return $response->redirect($this->permission->href);
            }
        }
        return $this->render('admin/item_add_form', ['model' => $this->model]);
    }

    public function editProduct(Request $request, Response $response, $productId)
    {
        $model = $this->model::find(['id' => $productId]);
        if ($request->isPost()) {
            $model->bindData($request->getBody());
            if ($model->validate() && $model->update()) {
                Application::$app->session->setFlashMessage(
                    "You successfully updated {$model->name} in the {$this->model->tableName()} table!",
                    'success'
                );
                return $response->redirect($this->permission->href);
            }
        }
        return $this->render('admin/item_add_form', ['model' => $model]);
    }
}
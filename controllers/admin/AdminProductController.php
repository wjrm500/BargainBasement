<?php

namespace app\controllers\admin;

use app\consts\BootstrapColorConsts;
use app\consts\ViewConsts;
use app\core\Application;
use app\core\LayoutTree;
use app\core\Request;
use app\core\Response;
use app\core\View;
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
        $this->addScript('/js/pagination.js');
        $this->layoutTree->customise([
            ViewConsts::ADMIN_ITEM_HOME => [
                ViewConsts::ADMIN_TABLE_HEADER,
                ViewConsts::ADMIN_TABLE_PAGES,
                ViewConsts::ADMIN_TABLE_PAGINATION
            ]
        ]);
        return $this->render();
    }

    public function addProduct(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $this->model->bindData($request->getBody());
            if ($this->model->validate() && $this->model->save()) {
                $this->app->session->setFlashMessage(
                    "You successfully added {$this->model->name} to the {$this->model->tableName()} table!",
                    BootstrapColorConsts::SUCCESS
                );
                return $response->redirect($this->permission->href);
            }
        }
        $this->layoutTree->customise(ViewConsts::ADMIN_ITEM_ADD);
        return $this->render(['model' => $this->model]);
    }

    public function editProduct(Request $request, Response $response, $productId)
    {
        $this->model->load(['id' => $productId]);
        if ($request->isPost()) {
            $this->model->bindData($request->getBody());
            if ($this->model->validate() && $this->model->update()) {
                $this->app->session->setFlashMessage(
                    "You successfully updated {$this->model->name} in the {$this->model->tableName()} table!",
                    BootstrapColorConsts::SUCCESS
                );
                return $response->redirect($this->permission->href);
            }
        }
        $this->layoutTree->customise(ViewConsts::ADMIN_ITEM_ADD);
        return $this->render(['model' => $this->model]);
    }

    public function deleteProduct(Request $request, Response $response, $productId)
    {
        $this->model->load(['id' => $productId]);
        if ($this->model->delete()) {
            $this->app->session->setFlashMessage(
                "You successfully deleted {$this->model->name} from the {$this->model->tableName()} table!",
                BootstrapColorConsts::DANGER
            );
            return $response->redirect($this->permission->href);
        };
    }
}
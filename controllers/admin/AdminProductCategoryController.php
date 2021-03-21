<?php

namespace app\controllers\admin;

use app\consts\BootstrapColorConsts;
use app\consts\ViewConsts;
use app\core\Request;
use app\core\Response;
use app\models\Permission;
use app\models\ProductCategory;
use app\models\ProductCategoryForm;

class AdminProductCategoryController extends AdminController
{
    public const PERMISSION_NAME = 'Product Categories';

    public function __construct()
    {
        $this->permission = Permission::find(['name' => self::PERMISSION_NAME]);
        $this->setModel(ProductCategoryForm::class);
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

    public function addProductCategory(Request $request, Response $response)
    {
        $this->addScript('/js/select2.js');
        $this->addScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        $this->addStylesheet('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
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

    public function editProductCategory(Request $request, Response $response, $productCategoryId)
    {
        $this->addScript('/js/select2.js');
        $this->addScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        $this->addStylesheet('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        $this->model = new ProductCategoryForm();
        $this->model->load(['id' => $productCategoryId]);
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

    public function deleteProductCategory(Request $request, Response $response, $productCategoryId)
    {
        $this->model->load(['id' => $productCategoryId]);
        if ($this->model->delete()) {
            $this->app->session->setFlashMessage(
                "You successfully deleted {$this->model->name} from the {$this->model->tableName()} table!",
                BootstrapColorConsts::DANGER
            );
            return $response->redirect($this->permission->href);
        };
    }
    
    public function search(Request $request, Response $response)
    {
        $searchTerm = $request->getBody()['search_term'];
        $items = $this->model::findAll();
        $searchItems = array_filter($items, fn($item) => str_starts_with(strtolower($item->name), strtolower($searchTerm)));
        return json_encode($this->renderViewOnly(ViewConsts::ADMIN_TABLE_PAGES, array_merge($this->getDefaultParams(), compact('searchItems'))));
    }
}
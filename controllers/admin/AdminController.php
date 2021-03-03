<?php

namespace app\controllers\admin;

use app\consts\ViewConsts;
use app\core\Application;
use app\core\Controller;
use app\core\db\DbModel;
use app\core\LayoutTree;
use app\core\middlewares\HasPermission;

abstract class AdminController extends Controller
{
    public const PERMISSION_NAME = '';

    public $permission;
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->addScript('/js/admin.js');
        $this->registerProtectedMethod('index', [new HasPermission($this->permission->id)]);
        $this->layoutTree->customise([
            ViewConsts::ADMIN => [
                ViewConsts::ADMIN_NAVBAR,
                ViewConsts::ADMIN_HEADER,
                LayoutTree::PLACEHOLDER
            ]
        ]);
    }

    protected function setModel($model)
    {
        $this->model = new $model();
    }

    protected function getDefaultParams()
    {
        return array_merge(
            parent::getDefaultParams(),
            [
                'itemAttributes' => $this->model::attributes(),
                'items'          => $this->model::findAll(),
                'permissionName' => static::PERMISSION_NAME,
            ]
        );
    }
}
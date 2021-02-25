<?php

namespace app\controllers\admin;

use app\core\Application;
use app\core\Controller;
use app\core\db\DbModel;
use app\core\middlewares\HasPermission;

abstract class AdminController extends Controller
{
    public const PERMISSION_NAME = '';

    public $permission;
    public $model;

    public function __construct()
    {
        $this->registerProtectedMethod('index', [new HasPermission($this->permission->id)]);
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

    public function render($view, $params = [])
    {
        $params = array_merge(
            $this->getDefaultParams(),
            $params
        );
        return parent::render($view, $params);
    }
}
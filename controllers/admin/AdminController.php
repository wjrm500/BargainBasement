<?php

namespace app\controllers\admin;

use app\core\Application;
use app\core\Controller;
use app\core\db\DbModel;
use app\core\middlewares\HasPermission;

abstract class AdminController extends Controller
{
    public const PERMISSION_NAME = '';

    public $permissionId;
    public $model;

    public function __construct()
    {
        $this->setLayout('admin');
        $this->registerProtectedMethod('index', [new HasPermission($this->permissionId)]);
    }

    protected function setModel($model)
    {
        $this->model = $model;
    }

    protected function getDefaultParams()
    {
        return [
            'itemAttributes' => $this->model::attributes(),
            'items'          => $this->model::findAll(),
            'permissionName' => static::PERMISSION_NAME,
            'permissions'    => Application::$app->getUser()->getPermissions(),
            'request'        => Application::$app->request
        ];
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
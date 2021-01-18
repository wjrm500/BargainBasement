<?php

namespace app\controllers\admin;

use app\core\Controller;
use app\core\middlewares\HasPermission;

abstract class AdminController extends Controller
{
    public $permissionId;

    public function __construct()
    {
        $this->setLayout('admin');
        $this->registerProtectedMethod('index', [new HasPermission($this->permissionId)]);
    }
}
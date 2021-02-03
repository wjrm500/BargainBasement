<?php

namespace app\controllers\admin;

use app\models\Permission;

class AdminCategoryController extends AdminController
{
    public const PERMISSION_NAME = 'Product Categories';

    public function __construct()
    {
        $this->permission = Permission::find(['name' => self::PERMISSION_NAME]);
        parent::__construct();
    }
}
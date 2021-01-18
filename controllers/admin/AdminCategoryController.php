<?php

namespace app\controllers\admin;

use app\models\Permission;

class AdminCategoryController extends AdminController
{
    public const PERMISSION_NAME = 'Product Categories';

    public function __construct()
    {
        $this->permissionId = Permission::find(['name' => self::PERMISSION_NAME])->id;
        parent::__construct();
    }
}
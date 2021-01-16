<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $adminPermissions = Application::$app->getUser()->getAdminPermissions();
        return $this->render('admin/home', compact('adminPermissions'));
    }
}
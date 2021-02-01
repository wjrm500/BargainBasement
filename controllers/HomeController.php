<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home');
    }

    public function adminIndex()
    {
        $permissions = Application::$app->getUser()->getPermissions();
        return $this->render('admin/home', compact('permissions'));
    }
}
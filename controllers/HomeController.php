<?php

namespace app\controllers;

use app\consts\ViewConsts;
use app\core\Controller;
use app\core\LayoutTree;
use app\core\Request;
use app\core\Response;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, Response $response)
    {
        // $this->layoutTree->customise(ViewConsts::HOME);
        // return $this->render();
        $response->redirect('/shop');
    }

    public function adminIndex()
    {
        $this->layoutTree->customise(ViewConsts::ADMIN_HOME);
        return $this->render();
    }
}
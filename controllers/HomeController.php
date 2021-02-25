<?php

namespace app\controllers;

use app\consts\ViewConsts;
use app\core\Controller;
use app\core\LayoutTree;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->layoutTree->customise([
            ViewConsts::FLASH_MESSAGES,
            ViewConsts::NAVBAR,
            LayoutTree::PLACEHOLDER,
        ]);
    }

    public function index()
    {
        $this->layoutTree->customise(ViewConsts::HOME);
        return $this->render();
    }

    public function adminIndex()
    {
        $this->layoutTree->customise(ViewConsts::ADMIN_HOME);
        return $this->render();
    }
}
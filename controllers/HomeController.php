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
        $this->layoutTree->replacePlaceholder([
            ViewConsts::VIEW_FLSH_MSGS,
            ViewConsts::VIEW_NAVBAR,
            LayoutTree::PLACEHOLDER,
        ]);
    }

    public function index()
    {
        $this->layoutTree->replacePlaceholder(ViewConsts::VIEW_HOME);
        return $this->render();
    }

    public function adminIndex()
    {
        $this->layoutTree->replacePlaceholder(ViewConsts::VIEW_ADMIN_HM);
        return $this->render();
    }
}
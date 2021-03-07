<?php

namespace app\controllers;

use app\consts\BootstrapColorConsts;
use app\consts\ViewConsts;
use app\core\Application;
use app\core\Controller;
use app\core\LayoutTree;
use app\core\middlewares\LoggedIn;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\RegisterForm;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerProtectedMethod('profile', [new LoggedIn()]);
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->bindData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $this->app->session->setFlashMessage(
                    'You have successfully logged in!',
                    BootstrapColorConsts::SUCCESS
                );
                return $response->redirect($request->getRedirectUrl() ?? '/');
            }
        }
        $this->layoutTree->customise(ViewConsts::LOGIN);
        return $this->render(compact('loginForm'));
    }

    public function register(Request $request, Response $response)
    {
        $registerForm = new RegisterForm();
        if ($request->isPost()) {
            $registerForm->bindData($request->getBody());
            if ($registerForm->validate() && $registerForm->save()) {
                $this->app->session->setFlashMessage(
                    'You have successfully registered!',
                    BootstrapColorConsts::SUCCESS
                );
                $this->app->login($registerForm);
                return $response->redirect('/');
            }
        }
        $this->layoutTree->customise(ViewConsts::REGISTER);
        return $this->render(compact('registerForm'));
    }

    public function logout(Request $request, Response $response)
    {
        $this->app->logout();
        $this->session->setFlashMessage(
            'You have successfully logged out!',
            BootstrapColorConsts::DANGER
        );
        return $response->redirect('/');
    }

    public function profile(Request $request, Response $response)
    {
        $this->layoutTree->customise(ViewConsts::PROFILE);
        return $this->render();
    }
}
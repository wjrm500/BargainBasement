<?php

namespace app\controllers;

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
        $this->layoutTree->replacePlaceholder([
            ViewConsts::VIEW_FLSH_MSGS,
            ViewConsts::VIEW_NAVBAR,
            LayoutTree::PLACEHOLDER,
        ]);
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->bindData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->session->setFlashMessage(
                    'You have successfully logged in!',
                    'success'
                );
                return $response->redirect($request->getRedirectUrl() ?? '/');
            }
        }
        $this->layoutTree->replacePlaceholder(ViewConsts::VIEW_LOGIN);
        return $this->render(compact('loginForm'));
    }

    public function register(Request $request, Response $response)
    {
        $registerForm = new RegisterForm();
        if ($request->isPost()) {
            $registerForm->bindData($request->getBody());
            if ($registerForm->validate() && $registerForm->save()) {
                Application::$app->session->setFlashMessage(
                    'You have successfully registered!',
                    'success'
                );
                Application::$app->login($registerForm);
                return $response->redirect('/');
            }
        }
        $this->layoutTree->replacePlaceholder(ViewConsts::VIEW_REGISTER);
        return $this->render(compact('registerForm'));
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        Application::$app->session->setFlashMessage(
            'You have successfully logged out!',
            'danger'
        );
        return $response->redirect('/');
    }

    public function profile(Request $request, Response $response)
    {
        $layoutTree = new LayoutTree([ViewConsts::VIEW_MAIN => [ViewConsts::VIEW_PROFILE]]);
        return $this->render($layoutTree);
    }
}
<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\LoggedIn;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\RegisterForm;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->registerProtectedMethod('profile', [new LoggedIn()]);
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
                return $response->redirect('/');
            }
        }
        return $this->render('login', compact('loginForm'));
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
        return $this->render('register', compact('registerForm'));
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
        $this->setLayout('main');
        return $this->render('profile');
    }
}
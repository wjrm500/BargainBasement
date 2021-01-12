<?php

namespace app\core;

use app\core\db\Database;
use app\models\LoginForm;
use app\models\RegisterForm;
use Exception;

class Application
{
    public static Application $app;
    public static string $root;
    public Request $request;
    public Response $response;
    public Router $router;
    public View $view;
    public Database $database;
    public Session $session;

    public function __construct($dbConfig)
    {
        self::$app = $this;
        self::$root = dirname(__DIR__);
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->view = new View();
        $this->database = new Database($dbConfig);
        $this->session = new Session();
    }

    public function login($userModel)
    {
        if ($userModel instanceof RegisterForm) {
            $this->session->set('user', $userModel->username);
        }
        if ($userModel instanceof LoginForm) {
            if ($dbUser = $userModel::find(['username' => $userModel->username])) {
                $dbPassword = $dbUser->password;
                if (password_verify($userModel->password, $dbPassword)) {
                    $this->session->set('user', $userModel->username);
                    return true;
                } else {
                    $this->session->setFlashMessage('Password incorrect', 'danger');
                    return false;
                }
            } else {
                $this->session->setFlashMessage('No user with that email exists', 'danger');
                    return false;
            }
        }
    }

    public function logout()
    {
        $this->session->remove('user');
    }

    public function hasUser()
    {
        return $this->session->has('user');
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request, $this->response);
        } catch (Exception $exception) {
            $this->response->setStatusCode($exception->getCode());
            echo $this->view->render('_error', compact('exception'));
        }
        exit();
    }
}
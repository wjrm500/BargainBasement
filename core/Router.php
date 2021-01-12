<?php

namespace app\core;

class Router
{
    public array $routes = [];

    public function __construct()
    {
        $this->request = Application::$app->request;
        $this->response = Application::$app->response;
        $routes = [
            'GET' => [],
            'POST' => []
        ];
    }

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve(Request $request, Response $response)
    {
        $method = $request->getMethod();
        $path = $request->getPath();
        $callback = $this->routes[$method][$path];
        if (is_array($callback)) {
            // Instantiate controller
            $callback[0] = new $callback[0]();
            $controller = $callback[0];
            $method = $callback[1];
            if ($controller->hasProtectedMethods()) {
                foreach ($controller->getProtectedMethods() as $protectedMethod) {
                    if ($method === $protectedMethod['method']) {
                        foreach ($protectedMethod['middlewares'] as $middleware) {
                            if (!$middleware->execute()) {
                                throw $middleware->getException();
                            }   
                        }
                    }
                }
            }
            // $protectedMethods = $controller->getProtectedMethods();
            // if (in_array($method, $protectedMethods)) {
            //     $protectedMethod = $protectedMethods[$method];
            //     foreach ($protectedMethod['middlewares'] as $middleware) {
            //         if (!$middleware->execute()) {
            //             throw $middleware->getException();
            //         }   
            //     }
            // }
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}
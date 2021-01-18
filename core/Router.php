<?php

namespace app\core;

use app\core\utilities\RegexTrait;

class Router
{
    use RegexTrait;

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
        if (!isset($this->routes['GET'][$path])) {
            $this->routes['GET'][$path] = $callback;
        }
    }

    public function post($path, $callback)
    {
        if (!isset($this->routes['POST'][$path])) {
            $this->routes['POST'][$path] = $callback;
        }
    }

    public function resolve(Request $request, Response $response)
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Check for regex matches

        foreach ($this->routes[$method] as $existingPath => $existingCallback) {
            if (preg_match('/{.*}/', $existingPath)) {
                $routeRegexPattern = $this->convertPathToRegexPattern($existingPath);
                if (preg_match($routeRegexPattern, $path, $matches)) {
                    $matches = array_slice($matches, 1);
                    $callback = $existingCallback;
                    break;
                }
            }
        }

        // If no regex match, try direct match

        if (!isset($matches)) {
            $callback = $this->routes[$method][$path];
        }

        // Instantiate controller and execute relevant middlewares

        if (is_array($callback)) {
            $callback[0] = new $callback[0](); // Instantiate controller
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
        }

        // Activate callback

        if (isset($matches)) {
            return call_user_func($callback, $this->request, $this->response, ...$matches);
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}
<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $route, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'handler' => $handler,
        ];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        foreach ($this->routes as $route) {
        
            if ($route['method'] !== $requestMethod) {
                continue;
            }

         
            $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route['route']);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); 

                $result =  call_user_func_array($route['handler'], $matches);
                echo $result;
                exit;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo json_encode(['error' => 'Not Found']);
        exit;
    }

    public function loadRoutesFromFile($routeFile)
    {
        $routes = require $routeFile;
        foreach ($routes as $route) {
            $this->add($route['method'], $route['path'], $route['action']);
        }
    }
}

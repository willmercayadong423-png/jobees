<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    public function registerRoute($method, $uri, $action, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function get($uri, $controller, $middleware = [])
    {
        $this->registerRoute('GET', $uri, $controller, $middleware);
    }

    public function post($uri, $controller, $middleware = [])
    {
        $this->registerRoute('POST', $uri, $controller, $middleware);
    }

    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoute('PUT', $uri, $controller, $middleware);
    }

    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoute('DELETE', $uri, $controller, $middleware);
    }

    public function error($httpCode = 404)
    {
        http_response_code($httpCode);

        loadView("error/{$httpCode}");

        exit;
    }

    public function route($uri, $method)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Method spoofing
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        // Current URI
        $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = '/WS03/public';

        if (str_starts_with($currentUri, $basePath)) {
            $currentUri = substr($currentUri, strlen($basePath));
        }

        $currentUri = '/' . trim($currentUri, '/');

        if ($currentUri === '/') {
            $currentUri = '/';
        }

        foreach ($this->routes as $route) {

            $pattern = preg_replace('/\{[^}]+\}/', '([^\/]+)', $route['uri']);

            $pattern = "#^" . $pattern . "$#";

            if (
                preg_match($pattern, $currentUri, $matches) &&
                $route['method'] === $requestMethod
            ) {

                array_shift($matches);

                $_GET['params'] = $matches;

                // Middleware
                foreach ($route['middleware'] as $middleware) {

    $middlewareClass = "Framework\\Middleware\\Authorize";

    $middlewareInstance = new $middlewareClass();

    $middlewareInstance->handle($middleware);
}
                $action = $route['action'];

                // Controller
                if (is_array($action)) {

                    [$class, $controllerMethod] = $action;

                    $controller = new $class();

                    return $controller->$controllerMethod($matches);
                }

                require basePath('App/' . $action);

                return;
            }
        }

        ErrorController::notFound();
    }
}
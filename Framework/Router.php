<?php
namespace Framework;

use App\controllers\ErrorController;

class Router
{
    protected $routes = [];

    public function RegisterRoute($method, $uri, $action)
{
    $this->routes[] = [
        'method' => $method,
        'uri' => $uri,
        'action' => $action
    ];
}

    public function get($uri, $controller)
    {
        $this->RegisterRoute('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        $this->RegisterRoute('POST', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        $this->RegisterRoute('PUT', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->RegisterRoute('DELETE', $uri, $controller);
    }

    public function error($httpCode = 404)
    {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }


public function show()
{
    $id = $_GET['params'][0] ?? null;

    $listing = $this->db
        ->query(
            'SELECT * FROM listings WHERE id = :id',
            ['id' => $id]
        )
        ->fetch(PDO::FETCH_OBJ);

    if (!$listing) {
        die('Listing not found');
    }

    loadView('listings/show', [
        'listing' => $listing
    ]);
}



 public function route($uri, $method)
{
    foreach ($this->routes as $route) {

        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route['uri']);
        $pattern = '@^' . $pattern . '$@';

        if (
            preg_match($pattern, $uri, $matches)
            && $route['method'] === strtoupper($method)
        ) {

            array_shift($matches);

            $_GET['params'] = $matches;

            $action = $route['action'];

            // MVC controller support
            if (is_array($action)) {

                [$class, $method] = $action;

                $controller = new $class();

                return $controller->$method();
            }

            // Old file-based support
            require basePath('App/' . $action);

            return;
        }
    }

    ErrorController::notFound();
}
}

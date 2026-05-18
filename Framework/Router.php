<?php
namespace Framework;

use App\Controllers\ErrorController;

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

  public function deleteRoute($uri, $controller)
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
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod === 'POST' && isset($_POST['_method'])) {
        $requestMethod = strtoupper($_POST['_method']);
    }

    // ✅ PLACE IT HERE (BEFORE LOOP)
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $basePath = '/WS03/public';

    if (str_starts_with($currentUri, $basePath)) {
        $currentUri = substr($currentUri, strlen($basePath));
    }

    $currentUri = '/' . trim($currentUri, '/');

    if ($currentUri === '') {
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

        $action = $route['action'];

        if (is_array($action)) {
            [$class, $controllerMethod] = $action;
            $controller = new $class();
            return $controller->$controllerMethod($_GET['params']);
        }

        require basePath('App/' . $action);
        return;
    }
}

    ErrorController::notFound();
}
}

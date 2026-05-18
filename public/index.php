

<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';
require basePath('Framework/Database.php');
use Framework\Database;
$config = require basePath('config/db.php');

$db = new Database($config);

require basePath('Framework/Router.php');
use Framework\Router;
$router = new Router();

require basePath('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptName = dirname($_SERVER['SCRIPT_NAME']);

if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
    $uri = substr($uri, strlen($scriptName));
}

$uri = '/' . trim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
<?php

use App\Controllers\HomeController;
use App\Controllers\ListingController;
use App\Controllers\UserController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/listings', [ListingController::class, 'index']);

$router->get('/listings/create', [ListingController::class, 'create']);
$router->get('/listings/edit/{id}', [ListingController::class, 'edit']);
$router->get('/listings/{id}', [ListingController::class, 'show']);

$router->post('/listings', [ListingController::class, 'store']);
$router->put('/listings/{id}', [ListingController::class, 'update']);
$router->deleteRoute('/listings/{id}', [ListingController::class, 'destroy']);

$router->get('/auth/register', [UserController::class, 'create']);
$router->get('/auth/login', [UserController::class, 'login']);


$router->post('/auth/register', [UserController::class, 'store']);
$router->post('/auth/login', [UserController::class, 'authenticate']);
$router->post('/auth/logout', [UserController::class, 'logout']);
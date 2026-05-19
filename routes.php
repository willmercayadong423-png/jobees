<?php

use App\Controllers\HomeController;
use App\Controllers\ListingController;
use App\Controllers\UserController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/listings', [ListingController::class, 'index']);

$router->get('/listings/create', [ListingController::class, 'create' ], ['auth']);
$router->get('/listings/edit/{id}', [ListingController::class, 'edit'], ['auth']);
$router->get('/listings/{id}', [ListingController::class, 'show']);

$router->post('/listings', [ListingController::class, 'store'], ['auth']);
$router->put('/listings/{id}', [ListingController::class, 'update'], ['auth']);
$router->delete('/listings/{id}', [ListingController::class, 'destroy'], ['auth']);

$router->get('/auth/register', [UserController::class, 'create'], ['guest']);
$router->get('/auth/login', [UserController::class, 'login'], ['guest']);


$router->post('/auth/register', [UserController::class, 'store'], ['guest']);
$router->post('/auth/logout', [UserController::class, 'logout'], ['auth']);
$router->post('/auth/login', [UserController::class, 'authenticate'], ['guest']);
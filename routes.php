<?php

use App\Controllers\HomeController;
use App\Controllers\ListingController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/listings', [ListingController::class, 'index']);

$router->get('/listings/create', [ListingController::class, 'create']);
$router->get('/listings/edit/{id}', [ListingController::class, 'edit']);
$router->get('/listings/{id}', [ListingController::class, 'show']);

$router->post('/listings', [ListingController::class, 'store']);
$router->put('/listings/{id}', [ListingController::class, 'update']);
$router->deleteRoute('/listings/{id}', [ListingController::class, 'destroy']);
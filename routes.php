<?php

use App\Controllers\HomeController;
use App\Controllers\ListingController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/listings', [ListingController::class, 'index']);

$router->get('/listings/create', [ListingController::class, 'create']);

$router->get('/listings/{id}', [ListingController::class, 'show']);
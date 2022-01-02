<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Badhabit\JwtLoginManagement\App\Route;
use Badhabit\JwtLoginManagement\Controller\SessionController;

Route::add('POST', '/api/auth/', SessionController::class, 'encoded');
Route::add('POST', '/api/decode/', SessionController::class, 'decoded');

Route::run();

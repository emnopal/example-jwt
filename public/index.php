<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Badhabit\SimpleJWT\Auth\Controller;
use Badhabit\SimpleJWT\Auth\Router;

Router::add('POST', '/api/auth/', Controller::class, 'encode');
Router::add('POST', '/api/decode/', Controller::class, 'decode');

Router::run();

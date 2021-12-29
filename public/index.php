<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Badhabit\JwtLoginManagement\Auth\Controller;
use Badhabit\JwtLoginManagement\Auth\Router;

Router::add('POST', '/api/auth/', Controller::class, 'encode');
Router::add('POST', '/api/decode/', Controller::class, 'decode');

Router::run();

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Badhabit\JwtLoginManagement\App\Router;
use Badhabit\JwtLoginManagement\Controller\Controller;

Router::add('POST', '/api/auth/', Controller::class, 'encode');
Router::add('POST', '/api/decode/', Controller::class, 'decode');

Router::run();

<?php

namespace Badhabit\JwtLoginManagement\Controller;

use Badhabit\JwtLoginManagement\Auth\Handler;
use Badhabit\JwtLoginManagement\Service\Service;

class Controller
{

    private Handler $handler;
    private Service $jwt;
    private string|false $input_raw;
    private array $input;

    public function __construct()
    {
        $this->handler = new Handler();
        $this->jwt = new Service($this->handler);
        $this->input_raw = file_get_contents('php://input');
        $this->input = (array)json_decode($this->input_raw);
    }

    public function decode()
    {
        echo json_encode($this->jwt->decode($this->input));
    }

    public function encode()
    {
        echo json_encode($this->jwt->encode($this->input));
    }

}
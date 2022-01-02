<?php

namespace Badhabit\JwtLoginManagement\Controller;

use Badhabit\JwtLoginManagement\Auth\Handler;
use Badhabit\JwtLoginManagement\Domain\Decode;
use Badhabit\JwtLoginManagement\Domain\UserSession;
use Badhabit\JwtLoginManagement\Repository\SessionRepository;
use Badhabit\JwtLoginManagement\Service\SessionService;

class SessionController
{

    private Handler $handler;
    private SessionService $sessionService;
    private string|false $input_raw;
    private array $input;
    private SessionRepository $sessionRepository;

    public function __construct()
    {
        $this->handler = new Handler();
        $this->sessionRepository = new SessionRepository($this->handler);
        $this->sessionService = new SessionService($this->sessionRepository);
        $this->input_raw = file_get_contents('php://input');
        $this->input = (array)json_decode($this->input_raw);
    }

    public function encoded()
    {
        try {
            if (!isset($this->input['username']) ||
                !isset($this->input['email'])) {
                throw new \Exception();
            }

            $userSession = new UserSession();
            $userSession->username = $this->input['username'];
            $userSession->email = $this->input['email'];

            echo json_encode($this->sessionService->encode($userSession));
        } catch (\Exception|\TypeError) {

            echo json_encode([
                "status" => [
                    "code" => 500,
                    "message" => "Require username or email"
                ],
            ]);
        }
    }

    public function decoded()
    {
        try {
            if (!isset($this->input['token'])) {
                throw new \Exception();
            }

            $decode = new Decode();
            $decode->token = $this->input['token'];

            echo json_encode($this->sessionService->decode($decode));
        } catch (\Exception|\TypeError $e) {

            echo json_encode([
                "status" => [
                    "code" => 500,
                    "message" => "Require username or email"
                ],
            ]);
        }
    }
}
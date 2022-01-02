<?php

namespace Badhabit\JwtLoginManagement\Service;

use Badhabit\JwtLoginManagement\Domain\Decode;
use Badhabit\JwtLoginManagement\Domain\UserSession;
use Badhabit\JwtLoginManagement\Helper\TimeStampConv;
use Badhabit\JwtLoginManagement\Repository\SessionRepository;

class SessionService
{

    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function encode(UserSession $userSession): array
    {
        try{
            $this->encodeValidate($userSession);
            $token = $this->sessionRepository->getToken($userSession);
            return [
                'status' => [
                    'code' => 200,
                    'message' => 'Success'
                ],
                'encoded_data' => [
                    'token_issued_timestamp' => $token->issuedAt,
                    'token_issued' => TimeStampConv::readableTimestamp($token->issuedAt),
                    'token_expires_timestamp' => $token->expireAt,
                    'token_expires' => TimeStampConv::readableTimestamp($token->expireAt),
                    'token' => $token->key,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    private function encodeValidate(UserSession $userSession): void
    {
        if (!isset($userSession->username) || $userSession->username == null) {
            throw new \Exception('Username is required');
        } else if (!isset($userSession->email) || $userSession->email == null ||
            !filter_var($userSession->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email is required');
        }
    }

    public function decode(Decode $decode): array
    {
        try{
            $this->decodeValidate($decode);
            $token = $this->sessionRepository->decodeToken($decode);
            return [
                'status' => [
                    'code' => 200,
                    'message' => 'Success'
                ],
                'token_data' => [
                    'who_issued' => $token->payload->iss,
                    'issued_timestamp' => $token->payload->iat,
                    'issued' => TimeStampConv::readableTimestamp($token->payload->iat),
                    'expires_timestamp' => $token->payload->exp,
                    'expires' => TimeStampConv::readableTimestamp($token->payload->exp),
                    'data' => $token->payload->data
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    public function decodeValidate(Decode $decode): void
    {
        if (!isset($decode->token) || $decode->token == null) {
            throw new \Exception('Token is required');
        }
    }

}
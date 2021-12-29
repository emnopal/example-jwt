<?php

namespace Badhabit\JwtLoginManagement\Auth;

class Service
{

    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function encode(array $data, ?string $url=null): array
    {
        if (is_null($url)) {
            $url = $_SERVER['PATH_INFO'];
        }
        try{
            $validated = $this->encodeValidate($data);
            $token = $this->handler->encode($url, $validated);
            return [
                'status' => [
                    'code' => 200,
                    'message' => 'Success'
                ],
                'data' => [
                    'token' => $token
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

    public function encodeValidate(array $json): array
    {
        if (!isset($json['username']) || $json['username'] == null) {
            throw new \Exception('Username is required');
        }
        if (!isset($json['password']) || $json['password'] == null) {
            throw new \Exception('Password is required');
        }
        if (!isset($json['email']) || $json['email'] == null ||
            !filter_var($json['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email is required');
        }
        if (in_array($json['username'], $json) &&
            in_array($json['password'], $json) &&
            in_array($json['email'], $json)) {
            return $json;
        } else {
            throw new \Exception('Body contains invalid fields');
        }
    }

    public function decode(array $data): array
    {
        try{
            $validated = $this->decodeValidate($data);
            $token = $this->handler->decode($validated['token']);
            return [
                'status' => [
                    'code' => 200,
                    'message' => 'Success'
                ],
                'data' => [
                    'token' => $token
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

    public function decodeValidate(array $json): array
    {
        if (!isset($json['token']) || $json['token'] == null) {
            throw new \Exception('Token is required');
        }
        if (in_array($json['token'], $json)) {
            return $json;
        } else {
            throw new \Exception('Body contains invalid fields');
        }
    }

}
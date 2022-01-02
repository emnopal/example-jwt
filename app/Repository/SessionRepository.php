<?php

namespace Badhabit\JwtLoginManagement\Repository;

use Badhabit\JwtLoginManagement\Auth\Handler;
use Badhabit\JwtLoginManagement\Domain\Decode;
use Badhabit\JwtLoginManagement\Domain\Encode;
use Badhabit\JwtLoginManagement\Domain\UserSession;
use Badhabit\JwtLoginManagement\Model\DecodedSession;
use Badhabit\JwtLoginManagement\Model\EncodedSession;

class SessionRepository
{
    private ?string $url;
    private Handler $handler;

    public function __construct(Handler $handler, ?string $url = null)
    {
        $this->handler = $handler;

        if (!$url) {
            if (!isset($_SERVER['HTTP_HOST']) && !isset($_SERVER['REQUEST_URI'])) {
                $this->url = "https://example.com";
            } else {
                $this->url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            }
        } else {
            $this->url = $url;
        }
    }

    public function getToken(UserSession $userSession): EncodedSession
    {
        $encode = new Encode();
        $encode->iss = $this->url;
        $encode->userSession = $userSession;

        return $this->handler->encode($encode);
    }

    public function decodeToken(Decode $decode): DecodedSession
    {
        return $this->handler->decode($decode);
    }
}
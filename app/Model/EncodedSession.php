<?php

namespace Badhabit\JwtLoginManagement\Model;

use Badhabit\JwtLoginManagement\Domain\Encode;

class EncodedSession
{
    public string $key;
    public string $issuedAt;
    public string $expireAt;
    public Encode $data;
}
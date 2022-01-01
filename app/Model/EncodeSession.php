<?php

namespace Badhabit\JwtLoginManagement\Model;

use Badhabit\JwtLoginManagement\Domain\Decoded;

class EncodeSession
{
    public string $key;
    public string $issued;
    public string $expires;
    public Decoded $data;
}
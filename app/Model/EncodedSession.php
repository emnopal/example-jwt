<?php

namespace Badhabit\JwtLoginManagement\Domain;

class Session
{
    public string $key;
    public string $issued;
    public string $expires;
    public Encode $data;
}
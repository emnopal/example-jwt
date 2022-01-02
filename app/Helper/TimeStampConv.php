<?php

namespace Badhabit\JwtLoginManagement\Helper;

class TimeStampConv
{
    public static function readableTimestamp(float|int|string $timestamp): string
    {
        $readable = \DateTime::createFromFormat('U', $timestamp);
        $readable->setTimezone(new \DateTimeZone("Asia/Jakarta"));
        $readable->format('Y-m-d H:i:s');
        $readable = (array)$readable;
        return $readable['date'];
    }
}
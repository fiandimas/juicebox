<?php

namespace App\Support;

class CacheKey
{
    public static function weather(string $city): string
    {
        return "weather:{$city}";
    }
}
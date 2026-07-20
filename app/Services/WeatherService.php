<?php

namespace App\Services;

use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;
use App\Clients\WeatherApiClient;
use Exception;

class WeatherService
{
    public function currentWeather(string $city): array
    {
        $keyCache = CacheKey::weather($city);
        $cachedWeather = Cache::get($keyCache);
        if (!is_null($cachedWeather)) return $cachedWeather;

       try {
          $weather = WeatherApiClient::fetchCurrentWeather($city);

          Cache::put($keyCache, $weather, now()->addMinutes(15));

          return $weather;
       } catch (Exception) {
            throw new Exception('MASOKK');
       }
    }
}

<?php

namespace App\Jobs;

use App\Clients\WeatherApiClient;
use App\Support\CacheKey;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class UpdateWeatherData implements ShouldQueue
{
    use Queueable;

    public function __construct() { }

    public function handle(): void
    {
        $city = 'Perth';
        $keyCache = CacheKey::weather($city);

        try {
            $weather = WeatherApiClient::fetchCurrentWeather($city);

            Cache::put($keyCache, $weather, now()->addMinutes(15));
        } catch (Exception $e) {
        }
    }
}

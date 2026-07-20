<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class WeatherApiClient
{
    public static function fetchCurrentWeather(string $city): array
    {
        $response = Http::timeout(5)
            ->retry(3, 100)
            ->get('http://api.weatherapi.com/v1/current.json', [
                'q' => $city,
                'key' => config('app.weather_api.key'),
                'aqi' => 'no',
            ]);

        return $response->throw()->json();
    }
}
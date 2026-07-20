<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Exception;

class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherService $weatherService
    ) { }

    public function index()
    {
        $city = 'Perth';
        $weather = $this->weatherService->currentWeather($city);

        return response()->json([
            'data' => $weather,
        ], 200);
    }
}

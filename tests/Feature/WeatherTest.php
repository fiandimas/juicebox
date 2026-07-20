<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    public function test_returns_weather_data_from_api(): void
    {
        Http::fake([
            'api.weatherapi.com/*' => Http::response([
                'location' => ['name' => 'Perth'],
                'current' => ['temp_c' => 22.0, 'condition' => ['text' => 'Sunny']],
            ], 200),
        ]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/weather');

        $response->assertOk()
            ->assertJsonPath('data.location.name', 'Perth');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.weatherapi.com')
                && $request['q'] === 'Perth';
        });
    }

    public function test_returns_cached_weather_without_calling_api_again(): void
    {
        $weather = [
            'location' => ['name' => 'Perth'],
            'current' => ['temp_c' => 22.0, 'condition' => ['text' => 'Sunny']],
        ];
        Cache::put(CacheKey::weather('Perth'), $weather, now()->addMinutes(15));

        Http::fake();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/weather');

        $response->assertOk()
            ->assertJsonPath('data.location.name', 'Perth');

        Http::assertNothingSent();
    }

    public function test_handles_external_api_failure(): void
    {
        Http::fake([
            'api.weatherapi.com/*' => Http::response([], 500),
        ]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/weather');

        $response->assertStatus(400);
    }

    protected function tearDown(): void
    {
        Cache::forget(CacheKey::weather('Perth'));
        parent::tearDown();
    }
}
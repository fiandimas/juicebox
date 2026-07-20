<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $payload = [
            'name' => 'Alfian Dimas Sugara',
            'email' => 'alfian.unit-test@mailtrap.io',
            'password' => 'Rahasia123'
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'alfian.unit-test@mailtrap.io'
        ]);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        $payload = [
            'name' => 'Alfian Dimas Sugara',
            'email' => 'alfian.unit-test@mailtrap.io',
            'password' => 'Rahasia123'
        ];

        User::factory()->create($payload);

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => 'VALIDATION_ERROR']);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $payload = [
            'email' => 'alfian.unit-test@mailtrap.io',
            'password' => 'Rahasia123'
        ];

        User::factory()->create($payload);

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $payload = [
            'email' => 'alfian.unit-test@mailtrap.io',
            'password' => 'Rahasia1234'
        ];

        User::factory()->create($payload);

        $response = $this->postJson('/api/login', [
            'email' => 'alfian.unit-test@mailtrap.io',
            'password' => 'Rahasia123'
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => 'VALIDATION_ERROR']);
    }
}

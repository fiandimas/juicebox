<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'title' => 'Membaca Buku Test',
            'content' => 'Membaca buku dapat meningkatkan fungsi kognitif test'
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Membaca Buku Test']);

        $this->assertDatabaseHas('posts', [
            'title' => 'Membaca Buku Test',
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $payload = [
            'title' => 'Membaca Buku Test',
            'content' => 'Membaca buku dapat meningkatkan fungsi kognitif test'
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response->assertStatus(401)
            ->assertJsonFragment(['error' => 'UNAUTHENTICATED']);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Membaca Buku Test'
        ]);
    }

    public function test_create_post_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'title' => '',
            'content' => ''
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => 'VALIDATION_ERROR']);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Membaca Buku Test',
        ]);
    }

    public function test_authenticated_user_can_list_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}

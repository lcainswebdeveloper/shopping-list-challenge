<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    public function test_we_cannot_create_a_shopping_list_if_we_not_logged_in(): void
    {
        $response = $this->getJson('/api/shopping-list');

        $response->assertStatus(401);
    }

    public function test_we_can_view_shopping_lists_if_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/shopping-list');

        $response->assertStatus(200);
    }

    public function test_a_guest_cannot_create_a_shopping_list(): void
    {
        $response = $this->postJson('/api/shopping-list');

        $response->assertStatus(401);
    }

    public function test_an_authenticated_user_can_create_a_shopping_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/shopping-list');
        $uuid = $response->json('data.id');
        $this->assertTrue(Str::isUuid($uuid), "The ID [{$uuid}] is not a valid UUID.");
        $this->assertDatabaseHas('shopping_lists', [
            'user_id' => $user->id,
        ]);
        $response->assertStatus(201);
    }
}

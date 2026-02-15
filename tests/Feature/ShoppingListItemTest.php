<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Database\Seeders\GrocerySeeder;
use Tests\TestCase;

class ShoppingListItemTest extends TestCase
{
    protected User $user;
    protected string $baseUrl;
    protected ShoppingList $shoppingList;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(GrocerySeeder::class);
        $this->user = User::factory()->create();
        $this->shoppingList = ShoppingList::create([
            'user_id' => $this->user->id,
        ]);
        $this->baseUrl = '/api/shopping-list/'.$this->shoppingList->id.'/items';
    }

    public function test_groceries_are_seeded(): void
    {
        $this->assertDatabaseHas('groceries', ['slug' => 'milk']);
    }

    public function test_we_cannot_create_a_shopping_list_item_if_we_are_not_logged_in(): void
    {
        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(401);
    }

    public function test_we_can_view_shopping_list_items_if_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get($this->baseUrl);

        $response->assertStatus(200);
    }

    public function test_a_guest_cannot_add_items_to_a_shopping_list(): void
    {
        $response = $this->postJson($this->baseUrl);

        $response->assertStatus(401);
    }

    public function test_an_authenticated_user_must_provide_items_in_the_shopping_list_items_payload(): void
    {
        // make sure we have items
        $payload = [];
        $response = $this->actingAs($this->user)->postJson($this->baseUrl, $payload);
        $response->assertStatus(422);
        $this->assertArrayHasKey('items', $response['errors']);

        $payload2 = [
            'items' => [],
        ];
        $response2 = $this->actingAs($this->user)->postJson($this->baseUrl, $payload2);
        $response2->assertStatus(422);
        $this->assertArrayHasKey('items', $response2['errors']);
    }

    public function test_an_authenticated_user_will_fail_validation_for_shopping_list_items(): void
    {
        $nonExistantItem = [
            'items' => [
                'i-dont-exist' => 6,
            ],
        ];

        $response2 = $this->actingAs($this->user)->postJson($this->baseUrl, $nonExistantItem);
        $this->assertEquals($response2['message'], "Sorry, you are trying to add groceries that don't exist");
        $response2->assertStatus(422);

        $payloadWithStringValue = [
            'items' => [
                'milk' => 2,
                'bread' => 'I should be a number',
                'eggs' => 3,
            ],
        ];
        $response3 = $this->actingAs($this->user)->postJson($this->baseUrl, $payloadWithStringValue);
        $this->assertEquals($response3['message'], 'You have added bread to your shopping list but it is not an integer.');
        $response3->assertStatus(422);

        $payloadWithBooleanValue = [
            'items' => [
                'milk' => 2,
                'bread' => 3,
                'eggs' => false,
            ],
        ];
        $response4 = $this->actingAs($this->user)->postJson($this->baseUrl, $payloadWithBooleanValue);
        $this->assertEquals($response4['message'], 'You have added eggs to your shopping list but it is not an integer.');
        $response4->assertStatus(422);
    }

    public function test_an_authenticated_user_can_create_a_shopping_list(): void
    {
        $payload = [
            'items' => [
                'milk' => 2,
                'bread' => 1,
                'eggs' => 3,
            ],
        ];
        $response = $this->actingAs($this->user)->postJson($this->baseUrl, $payload);
        $response->assertStatus(201);
        foreach ($payload['items'] as $item => $quantity) {
            $this->assertDatabaseHas('shopping_list_items', [
                'shopping_list_id' => $this->shoppingList->id,
                'grocery_slug' => $item,
                'quantity' => $quantity,
            ]);
        }

        $payloadUpdate = [
            'items' => [
                'milk' => 6,
                'bread' => 3,
                'eggs' => 4,
                'rice' => 2,
            ],
        ];

        $response = $this->actingAs($this->user)->postJson($this->baseUrl, $payloadUpdate);
        $updatedCount = ShoppingListItem::where('shopping_list_id', $this->shoppingList->id)->get();
        $this->assertCount(4, $updatedCount);
        $response->assertStatus(201);
        foreach ($payloadUpdate['items'] as $item => $quantity) {
            $this->assertDatabaseHas('shopping_list_items', [
                'shopping_list_id' => $this->shoppingList->id,
                'grocery_slug' => $item,
                'quantity' => $quantity,
            ]);
        }
    }
}

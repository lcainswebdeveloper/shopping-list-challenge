<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Grocery;
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
    /**
     * @var \Illuminate\Support\Collection<string, int>
     */
    protected \Illuminate\Support\Collection $groceryList;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(GrocerySeeder::class);
        $this->groceryList = Grocery::lookup();
        $this->user = User::factory()->create();
        $this->shoppingList = ShoppingList::create([
            'user_id' => $this->user->id,
        ]);
        $this->baseUrl = '/api/shopping-list/' . $this->shoppingList->id . '/items';
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
        $response = $this->actingAs($this->user)->get($this->baseUrl);

        $response->assertStatus(200);
    }

    public function test_we_cannot_view_shopping_list_items_if_authenticated_but_shopping_list_isnt_ours(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get($this->baseUrl);

        $response->assertStatus(403);
    }

    public function test_a_guest_cannot_add_items_to_a_shopping_list(): void
    {
        $response = $this->postJson($this->baseUrl);

        $response->assertStatus(401);
    }

    public function test_another_user_cannot_add_items_to_our_shopping_list(): void
    {
        $user = User::factory()->create();
        $payload = [
            'items' => [
                'milk' => 6,
                'bread' => 3,
                'eggs' => 4,
                'rice' => 2,
            ],
        ];

        $response = $this->actingAs($user)->postJson($this->baseUrl, $payload);
        $response->assertStatus(403);
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
        $nonExistentItem = [
            'items' => [
                'i-dont-exist' => 6,
            ],
        ];

        $response2 = $this->actingAs($this->user)->postJson($this->baseUrl, $nonExistentItem);
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

    public function test_an_authenticated_user_can_add_and_update_shopping_list_items_with_immutable_pricing(): void
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

        foreach ($response['data']['items'] as $item) {
            $groceryItemUnitPrice = $this->groceryList[$item['grocery_slug']];
            $payloadItem = $payload['items'][$item['grocery_slug']];
            $this->assertEquals($payloadItem, $item['quantity']);
            $this->assertEquals($groceryItemUnitPrice, $item['unit_price_in_pence']);
            $this->assertEquals($groceryItemUnitPrice * $item['quantity'], $item['total_price_in_pence']);

            $this->assertDatabaseHas('shopping_list_items', [
                'shopping_list_id' => $this->shoppingList->id,
                'grocery_slug' => $item['grocery_slug'],
                'quantity' => $item['quantity'],
                'unit_price_in_pence' => $item['unit_price_in_pence'],
                'total_price_in_pence' => $item['total_price_in_pence'],
            ]);
        }

        $originalMilkPrice = Grocery::firstWhere('slug', 'milk')->unit_price_in_pence;
        Grocery::where('slug', 'milk')->update([
            'unit_price_in_pence' => 200,
        ]);

        $this->groceryList = Grocery::lookup(); // reset the list
        $payloadUpdate = [
            'items' => [
                'milk' => 6,
                'bread' => 3,
                'eggs' => 4,
                'rice' => 2,
            ],
        ];

        $responseUpdate = $this->actingAs($this->user)->postJson($this->baseUrl, $payloadUpdate);
        $updatedCount = ShoppingListItem::where('shopping_list_id', $this->shoppingList->id)->get();
        $this->assertCount(4, $updatedCount);
        $responseUpdate->assertStatus(201);
        foreach ($responseUpdate['data']['items'] as $item) {
            $isMilk = $item['grocery_slug'] === 'milk';
            if ($isMilk) {
                // we should have kept the original created price even though its been updated
                $groceryItemUnitPrice = $originalMilkPrice;
                $payloadItem = $payloadUpdate['items'][$item['grocery_slug']];
                $this->assertEquals($payloadItem, $item['quantity']);
                $this->assertEquals($groceryItemUnitPrice, $item['unit_price_in_pence']);
                $this->assertEquals($groceryItemUnitPrice * $item['quantity'], $item['total_price_in_pence']);

                $this->assertDatabaseHas('shopping_list_items', [
                    'shopping_list_id' => $this->shoppingList->id,
                    'grocery_slug' => $item['grocery_slug'],
                    'quantity' => $item['quantity'],
                    'unit_price_in_pence' => $groceryItemUnitPrice,
                    'total_price_in_pence' => $item['total_price_in_pence'],
                ]);
            } else {
                $groceryItemUnitPrice = $this->groceryList[$item['grocery_slug']];
                $payloadItem = $payloadUpdate['items'][$item['grocery_slug']];
                $this->assertEquals($payloadItem, $item['quantity']);
                $this->assertEquals($groceryItemUnitPrice, $item['unit_price_in_pence']);
                $this->assertEquals($groceryItemUnitPrice * $item['quantity'], $item['total_price_in_pence']);

                $this->assertDatabaseHas('shopping_list_items', [
                    'shopping_list_id' => $this->shoppingList->id,
                    'grocery_slug' => $item['grocery_slug'],
                    'quantity' => $item['quantity'],
                    'unit_price_in_pence' => $item['unit_price_in_pence'],
                    'total_price_in_pence' => $item['total_price_in_pence'],
                ]);
            }
        }
    }
}

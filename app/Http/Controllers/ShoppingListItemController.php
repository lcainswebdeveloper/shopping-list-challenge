<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Http\Resources\GroceryWithSelectionResource;
use App\Http\Resources\ShoppingListResource;
use App\Models\Grocery;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Services\ShoppingListItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingListItemController extends Controller
{
    public function __construct(private readonly ShoppingListItemService $shoppingListItemService) {}

    /**
     * Get the current shopping list for the customer with the items
     */
    public function index(Request $request, ShoppingList $shoppingList): JsonResponse
    {
        $groceries = Grocery::orderBy('slug')->get();
        $selectedItems = $shoppingList->items->keyBy('grocery_slug');

        $groceriesWithSelection = $groceries->map(
            fn(Grocery $grocery) => new GroceryWithSelectionResource(
                $grocery,
                $selectedItems->get($grocery->slug)
            )
        );

        return response()->json([
            'data' => [
                'shopping_list' => (new ShoppingListResource($shoppingList))->resolve(),
                'groceries' => $groceriesWithSelection->map->resolve(),
            ],
        ], 200);
    }

    /**
     * Create / update the shopping list items
     */
    public function store(ShoppingListRequest $request, ShoppingList $shoppingList): JsonResponse
    {
        $this->shoppingListItemService->upsert($shoppingList, $request->validated()['items']);
        // Reload items to get updated data
        $shoppingList->load('items');
        $this->shoppingListItemService->updateSubtotal($shoppingList);

        return (new ShoppingListResource($shoppingList))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Remove the item from the shopping list.
     */
    public function destroy(ShoppingList $shoppingList, string $slug): Response
    {
        ShoppingListItem::where(['grocery_slug' => $slug, 'shopping_list_id' => $shoppingList->id])->delete();
        $shoppingList->load('items');
        $this->shoppingListItemService->updateSubtotal($shoppingList);

        return response()->noContent();
    }
}

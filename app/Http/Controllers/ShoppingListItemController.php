<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use App\Services\ShoppingListItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingListItemController extends Controller
{
    public function __construct(private readonly ShoppingListItemService $shoppingListItemService) {}

    /**
     * Get the current shopping list for the customer with the items
     */
    public function index(Request $request, ShoppingList $shoppingList): JsonResponse
    {
        // Reload items to get updated data
        $shoppingList->load('items');

        return (new ShoppingListResource($shoppingList))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create / update the shopping list items
     */
    public function store(ShoppingListRequest $request, ShoppingList $shoppingList): JsonResponse
    {
        $this->shoppingListItemService->upsert($shoppingList, $request->validated()['items']);
        // Reload items to get updated data
        $shoppingList->load('items');

        return (new ShoppingListResource($shoppingList))
            ->response()
            ->setStatusCode(201);
    }
}

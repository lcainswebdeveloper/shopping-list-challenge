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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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

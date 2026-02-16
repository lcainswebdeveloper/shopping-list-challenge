<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ShoppingList $shoppingList): void {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShoppingListRequest $request, ShoppingList $shoppingList): JsonResponse
    {
        $request->upsert($shoppingList);

        return response()->json('Items added successfully to shopping list', 201);
    }
}

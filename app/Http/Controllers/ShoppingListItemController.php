<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Http\Request;

class ShoppingListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ShoppingList $shoppingList) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShoppingListRequest $request, ShoppingList $shoppingList)
    {
        $request->upsert($shoppingList);
        return response()->json('Items added successfully to shopping list', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingListItem $shoppingListItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShoppingListItem $shoppingListItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShoppingListItem $shoppingListItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoppingListItem $shoppingListItem)
    {
        //
    }
}

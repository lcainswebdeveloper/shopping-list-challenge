<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): ShoppingListResource
    {
        $user = $request->user();
        if (! $user) {
            abort(401, 'No user found');
        }
        $created = ShoppingList::create([
            'user_id' => $user->id,
        ]);

        return new ShoppingListResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingList $shoppingList): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShoppingList $shoppingList): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShoppingList $shoppingList): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoppingList $shoppingList): void
    {
        //
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401, 'No user found');
        }
        $userShoppingLists = ShoppingList::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return ShoppingListResource::collection($userShoppingLists)->response()
            ->setStatusCode(200);
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
}

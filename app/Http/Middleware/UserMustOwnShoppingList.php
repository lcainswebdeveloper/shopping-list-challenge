<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ShoppingList;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMustOwnShoppingList
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shoppingList = $request->route('shopping_list');
        if ($shoppingList instanceof ShoppingList) {
            if ($shoppingList->user_id !== $request->user()?->id) {
                return response()->json([
                    'message' => 'Sorry you are not the owner of this shopping list.',
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Shopping List not found.',
            ], 404);
        }

        return $next($request);
    }
}

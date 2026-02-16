<?php

declare(strict_types=1);

use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\ShoppingListItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('/shopping-list', [ShoppingListController::class, 'index']);
    Route::post('/shopping-list', [ShoppingListController::class, 'store']);

    Route::middleware(['owns.shopping.list'])->group(function (): void {
        Route::resource('shopping-list.items', ShoppingListItemController::class)
            ->only(['index', 'store']);
    });
});

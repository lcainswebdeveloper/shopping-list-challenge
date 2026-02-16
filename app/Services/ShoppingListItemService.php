<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Grocery;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;

class ShoppingListItemService
{
    /**
     * Undocumented function
     *
     * @param  array<string, int>  $items
     */
    public function upsert(ShoppingList $shoppingList, array $items): int
    {
        $groceryKeys = array_keys($items);
        $validGroceries = Grocery::whereIn('slug', $groceryKeys)->get(['slug', 'unit_price_in_pence'])->keyBy('slug');
        // Get existing shopping list items (to preserve their original unit prices)
        $existingItems = ShoppingListItem::where('shopping_list_id', $shoppingList->id)
            ->whereIn('grocery_slug', $groceryKeys)
            ->get(['grocery_slug', 'unit_price_in_pence'])
            ->keyBy('grocery_slug');

        $data = array_map(
            function (int $quantity, string $slug) use ($validGroceries, $existingItems, $shoppingList): array {
                // Use existing unit price if item exists, otherwise use current grocery price
                $existingItem = $existingItems->get($slug);
                $grocery = $validGroceries->get($slug);

                $unitPrice = $existingItem !== null
                    ? $existingItem->unit_price_in_pence
                    : ($grocery->unit_price_in_pence ?? 0);

                return [
                    'quantity' => $quantity,
                    'unit_price_in_pence' => $unitPrice,
                    'total_price_in_pence' => $unitPrice * $quantity,
                    'grocery_slug' => $slug,
                    'shopping_list_id' => $shoppingList->id,
                ];
            },
            $items,
            $groceryKeys
        );

        return ShoppingListItem::upsert(
            $data,
            ['shopping_list_id', 'grocery_slug'],
            ['quantity', 'total_price_in_pence', 'unit_price_in_pence'],
        );
    }
}

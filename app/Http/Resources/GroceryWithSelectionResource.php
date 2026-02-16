<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ShoppingListItem;
use Illuminate\Http\Request;

/**
 * @mixin \App\Models\Grocery
 */
class GroceryWithSelectionResource extends GroceryResource
{
    private ?ShoppingListItem $selectedItem;

    public function __construct($resource, ?ShoppingListItem $selectedItem = null)
    {
        parent::__construct($resource);
        $this->selectedItem = $selectedItem;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'selected' => $this->selectedItem !== null,
        ]);
    }
}

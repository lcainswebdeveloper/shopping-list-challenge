<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ShoppingListItem
 */
class ShoppingListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shopping_list_id' => $this->shopping_list_id,
            'grocery_slug' => $this->grocery_slug,
            'quantity' => $this->quantity,
            'unit_price_in_pence' => $this->unit_price_in_pence,
            'total_price_in_pence' => $this->total_price_in_pence,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

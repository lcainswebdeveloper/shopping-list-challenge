<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Contracts\MoneyFormatInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ShoppingList
 */
class ShoppingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var MoneyFormatInterface $formatter */
        $formatter = app(MoneyFormatInterface::class);

        return [
            'id' => $this->id,
            'items' => ShoppingListItemResource::collection($this->whenLoaded('items')),
            'formatted_subtotal' => $formatter->format($this->subtotal_in_pence ?? 0),
            'currency' => $formatter->getCurrencyCode(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Contracts\MoneyFormatInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Grocery
 */
class GroceryResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'unit_price_in_pence' => $this->unit_price_in_pence,
            'formatted_price' => $formatter->format($this->unit_price_in_pence),
            'currency' => $formatter->getCurrencyCode(),
        ];
    }
}

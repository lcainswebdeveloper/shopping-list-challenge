<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['grocery_slug', 'shopping_list_id', 'quantity', 'unit_price_in_pence', 'total_price_in_pence'];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_in_pence' => 'integer',
        'total_price_in_pence' => 'integer',
    ];

    /**
     * @return BelongsTo<ShoppingList, $this>
     */
    public function shoppingList(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }
}

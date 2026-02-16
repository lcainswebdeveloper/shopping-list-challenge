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
    protected $fillable = ['grocery_slug', 'shopping_list_id', 'quantity', 'cost_in_pence'];

    /**
     * @return BelongsTo<ShoppingList, $this>
     */
    protected function list(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }
}

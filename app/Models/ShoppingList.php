<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingList extends Model
{
    use HasUuids;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['user_id'];

    /**
     * @return HasMany<ShoppingListItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(ShoppingListItem::class);
    }
}

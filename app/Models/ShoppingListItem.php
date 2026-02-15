<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['grocery_slug', 'shopping_list_id', 'quantity', 'cost_in_pence'];

    protected function list()
    {
        return $this->belongsTo(ShoppingList::class);
    }
}

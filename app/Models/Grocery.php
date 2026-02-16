<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Grocery extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'slug';

    protected $fillable = [
        'slug',
        'name',
        'unit_price_in_pence',
    ];

    protected $casts = ['unit_price_in_pence' => 'integer'];

    /**
     * Get a lookup dictionary of slug => unit_price_in_pence.
     *
     * @return Collection<string, int>
     */
    public static function lookup(): Collection
    {
        return self::all()->pluck('unit_price_in_pence', 'slug');
    }
}

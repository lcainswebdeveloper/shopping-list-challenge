<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grocery extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    /** @use HasFactory<\Database\Factories\GroceryFactory> */
    use HasFactory;
}

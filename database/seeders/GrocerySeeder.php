<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrocerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groceries = [
            ['slug' => 'milk', 'name' => 'Milk', 'unit_price_in_pence' => 120],
            ['slug' => 'bread', 'name' => 'Bread', 'unit_price_in_pence' => 100],
            ['slug' => 'eggs', 'name' => 'Eggs', 'unit_price_in_pence' => 200],
            ['slug' => 'cheese', 'name' => 'Cheese', 'unit_price_in_pence' => 250],
            ['slug' => 'apples', 'name' => 'Apples', 'unit_price_in_pence' => 150],
            ['slug' => 'bananas', 'name' => 'Bananas', 'unit_price_in_pence' => 130],
            ['slug' => 'chicken-breast', 'name' => 'Chicken Breast', 'unit_price_in_pence' => 400],
            ['slug' => 'rice', 'name' => 'Rice', 'unit_price_in_pence' => 90],
            ['slug' => 'pasta', 'name' => 'Pasta', 'unit_price_in_pence' => 110],
            ['slug' => 'tomatoes', 'name' => 'Tomatoes', 'unit_price_in_pence' => 140],
            ['slug' => 'potatoes', 'name' => 'Potatoes', 'unit_price_in_pence' => 120],
            ['slug' => 'onions', 'name' => 'Onions', 'unit_price_in_pence' => 80],
            ['slug' => 'carrots', 'name' => 'Carrots', 'unit_price_in_pence' => 90],
            ['slug' => 'butter', 'name' => 'Butter', 'unit_price_in_pence' => 180],
            ['slug' => 'yogurt', 'name' => 'Yogurt', 'unit_price_in_pence' => 110],
            ['slug' => 'orange-juice', 'name' => 'Orange Juice', 'unit_price_in_pence' => 160],
            ['slug' => 'cereal', 'name' => 'Cereal', 'unit_price_in_pence' => 210],
            ['slug' => 'bacon', 'name' => 'Bacon', 'unit_price_in_pence' => 350],
            ['slug' => 'lettuce', 'name' => 'Lettuce', 'unit_price_in_pence' => 100],
            ['slug' => 'cucumber', 'name' => 'Cucumber', 'unit_price_in_pence' => 90],
        ];

        foreach ($groceries as $grocery) {
            DB::table('groceries')->insertOrIgnore($grocery);
        }
    }
}

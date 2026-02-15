<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['slug' => 'milk', 'name' => 'Milk', 'price_in_units' => 120],
            ['slug' => 'bread', 'name' => 'Bread', 'price_in_units' => 100],
            ['slug' => 'eggs', 'name' => 'Eggs', 'price_in_units' => 200],
            ['slug' => 'cheese', 'name' => 'Cheese', 'price_in_units' => 250],
            ['slug' => 'apples', 'name' => 'Apples', 'price_in_units' => 150],
            ['slug' => 'bananas', 'name' => 'Bananas', 'price_in_units' => 130],
            ['slug' => 'chicken-breast', 'name' => 'Chicken Breast', 'price_in_units' => 400],
            ['slug' => 'rice', 'name' => 'Rice', 'price_in_units' => 90],
            ['slug' => 'pasta', 'name' => 'Pasta', 'price_in_units' => 110],
            ['slug' => 'tomatoes', 'name' => 'Tomatoes', 'price_in_units' => 140],
            ['slug' => 'potatoes', 'name' => 'Potatoes', 'price_in_units' => 120],
            ['slug' => 'onions', 'name' => 'Onions', 'price_in_units' => 80],
            ['slug' => 'carrots', 'name' => 'Carrots', 'price_in_units' => 90],
            ['slug' => 'butter', 'name' => 'Butter', 'price_in_units' => 180],
            ['slug' => 'yogurt', 'name' => 'Yogurt', 'price_in_units' => 110],
            ['slug' => 'orange-juice', 'name' => 'Orange Juice', 'price_in_units' => 160],
            ['slug' => 'cereal', 'name' => 'Cereal', 'price_in_units' => 210],
            ['slug' => 'bacon', 'name' => 'Bacon', 'price_in_units' => 350],
            ['slug' => 'lettuce', 'name' => 'Lettuce', 'price_in_units' => 100],
            ['slug' => 'cucumber', 'name' => 'Cucumber', 'price_in_units' => 90],
        ];

        foreach ($groceries as $grocery) {
            DB::table('groceries')->insert($grocery);
        }
    }
}

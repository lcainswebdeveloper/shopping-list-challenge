<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Grocery;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ShoppingListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // todo - add logic to make sure the list belongs to the user
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => [
                'required',
                'array',
                'min:1',
                function ($attr, $val, $fail) {
                    $groceryKeys = array_keys($val);
                    $validGroceries = Grocery::whereIn('slug', $groceryKeys)->count();
                    if (count($groceryKeys) !== $validGroceries) {
                        $fail("Sorry, you are trying to add groceries that don't exist");
                    }
                },
            ],
            'items.*' => ['required'],
        ];
    }

    public function upsert(ShoppingList $shoppingList): int
    {
        $items = $this->items;
        $groceryKeys = array_keys($items);
        $validGroceries = Grocery::whereIn('slug', $groceryKeys)->get(['slug', 'price_in_units'])->keyBy('slug');
        $data = array_map(function ($quantity, $slug) use ($validGroceries, $shoppingList) {
            $groceryRecord = $validGroceries[$slug];

            return [
                'quantity' => $quantity,
                'cost_in_units' => ($groceryRecord->price_in_units ?? 0) * $quantity,
                'grocery_slug' => $slug,
                'shopping_list_id' => $shoppingList->id,
            ];
        }, $items, $groceryKeys);
        $saved = ShoppingListItem::upsert(
            $data,
            ['shopping_list_id', 'grocery_slug'],
            ['quantity', 'cost_in_units'],
        );

        return $saved;
    }

    // This is to make sure that the validator has nice messages about the grocery item eg bread instead of items.bread
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->items ?? [] as $slug => $quantity) {
                if (! is_int($quantity)) {
                    $validator->errors()->add(
                        "items.$slug",
                        "You have added $slug to your shopping list but it is not an integer."
                    );
                } elseif ($quantity < 1) {
                    $validator->errors()->add(
                        "items.$slug",
                        "You have added $slug to your shopping list but the quantity must be at least 1."
                    );
                }
            }
        });
    }
}

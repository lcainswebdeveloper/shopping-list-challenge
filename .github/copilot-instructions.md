# GitHub Copilot Instructions

## Project Overview

This is a **Laravel 12 Shopping List API** for a 6-hour coding challenge. The goal is to demonstrate senior-level PHP skills through a well-architected, secure, and tested shopping list application.

## Tech Stack

- **Framework:** Laravel 12
- **PHP Version:** 8.4
- **Database:** SQLite
- **Authentication:** Laravel Sanctum (via Breeze API)
- **Static Analysis:** PHPStan Level 8 (Larastan)
- **Code Style:** Laravel Pint (PSR-12)
- **Testing:** PHPUnit
- **Containerization:** Laravel Sail (Docker)
- **Email:** Mailpit (local testing)

## Coding Standards

### PHP 8.4 Modern Features

Always use modern PHP features:

```php
// ✅ declare strict types in every file
declare(strict_types=1);

// ✅ Type hints on all parameters and return types
public function store(StoreItemRequest $request): JsonResponse

// ✅ Constructor property promotion
public function __construct(
    private readonly ShoppingListService $service
) {}

// ✅ Enums for fixed values
enum ItemStatus: string
{
    case Pending = 'pending';
    case Purchased = 'purchased';
}

// ✅ Match expressions over switch
$message = match($status) {
    ItemStatus::Pending => 'Still needed',
    ItemStatus::Purchased => 'Already bought',
};
```

### Laravel Idioms

Follow Laravel conventions:

```php
// ✅ Form Requests for validation
class StoreShoppingItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price_in_pence' => ['required', 'integer', 'min:0'],
        ];
    }
}

// ✅ API Resources for JSON transformation
class ShoppingItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price_in_pence' => $this->price_in_pence,
        ];
    }
}

// ✅ Policies for authorization
class ShoppingItemPolicy
{
    public function update(User $user, ShoppingItem $item): bool
    {
        return $user->id === $item->user_id;
    }
}

// ✅ Service classes for business logic
class ShoppingListService
{
    public function calculateTotal(User $user): int
    {
        return $user->shoppingItems()->sum('price_in_pence');
    }
}
```

### Financial Precision

**Critical:** Always use integers for currency:

```php
// ✅ Store as pence (integer)
$table->unsignedInteger('price_in_pence')->default(0);

// ✅ Calculate with integers
$total = $items->sum('price_in_pence'); // Returns integer

// ✅ Format for display only
$formatted = number_format($total / 100, 2); // "12.50"

// ❌ Never use floats for money
$price = 12.50; // WRONG - floating point precision issues
```

### Security - Ownership Validation

**Critical:** Users must only access their own items:

```php
// ✅ Scope queries to authenticated user
$items = auth()->user()->shoppingItems()->get();

// ✅ Policy authorization
$this->authorize('update', $shoppingItem);

// ✅ Form Request authorization
public function authorize(): bool
{
    $item = $this->route('shopping_item');
    return $this->user()->id === $item->user_id;
}

// ❌ Never allow cross-user access
ShoppingItem::find($id); // WRONG - no ownership check
```

## Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Thin controllers
│   │   ├── Requests/        # Form Requests for validation
│   │   └── Resources/       # API Resources for JSON
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Services/            # Business logic
├── database/
│   ├── factories/           # Model factories for testing
│   └── migrations/          # Database schema
├── routes/
│   └── api.php              # API routes (Sanctum protected)
└── tests/
    └── Feature/             # Feature tests
```

## User Stories Reference

1. View shopping list items
2. Add items to list
3. Remove items from list
4. Mark items as purchased
5. Persist data between visits
6. Reorder items
7. Calculate total price
8. Spending limit alerts
9. Share list via email
10. User authentication (Breeze API - done)

## Commands

```bash
# Start containers
./vendor/bin/sail up -d

# Run tests
./vendor/bin/sail composer test

# Static analysis
./vendor/bin/sail composer analyse

# Code formatting
./vendor/bin/sail composer format

# Lint check (no fixes)
./vendor/bin/sail composer lint

# Run migrations
./vendor/bin/sail artisan migrate
```

## Agent Commands

Say these phrases to invoke specialized agents:

- **"Please code review this branch"** - Runs code review against main
- **"Generate commit message"** - Creates conventional commit message
- **"Generate PR description"** - Creates comprehensive PR description

## Quality Gates

Before merging, ensure:

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Pint passes (`composer lint`)
- [ ] All tests pass (`composer test`)
- [ ] Ownership validation implemented
- [ ] Currency handled as integers
- [ ] Form Requests used for validation
- [ ] API Resources used for responses

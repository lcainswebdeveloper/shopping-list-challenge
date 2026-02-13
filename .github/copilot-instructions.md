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

## Commit Message Generation

**Important:** When you request a commit message (e.g., "Generate commit message"), the workflow in `.github/commit-message-generator.md` **must** be followed. This file defines the required process, prompts, and formatting for commit messages, including:

- Checking for staged changes
- Analyzing the diff and change types
- Using Conventional Commits format
- Presenting the message and offering to commit

**Do not use any other process for commit messages.**

## Code Review Generation

**Important:** When you request a code review (e.g., "Please code review this branch"), the workflow in `.github/reviews/laravel-codereview.md` **must** be followed. This file defines the required process, checks, scoring, and reporting for code reviews, including:

- Pre-review validation (uncommitted changes, branch detection)
- Automated checks (PHPStan, Pint, PHPUnit)
- Git diff and file-by-file analysis
- Scoring and severity classification
- Report generation and saving

**Do not use any other process for code reviews.**

## PR Description Generation

**Important:** When you request a PR description (e.g., "Generate PR description"), the workflow in `.github/pr-generator.md` **must** be followed. This file defines the required process, prompts, and formatting for PR descriptions, including:

- Branch and diff analysis
- Commit and file change categorization
- PR type detection
- Comprehensive PR description generation and saving

**Do not use any other process for PR descriptions.**

## Agent Commands

Say these phrases to invoke specialized agents:

- **"Please code review this branch"** - Runs code review against main
- **"Generate commit message"** - Triggers the commit message workflow as defined in `.github/commit-message-generator.md`
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

---

# Mayden Shopping List API

A modern Laravel 12 REST API for managing shopping lists, demonstrating senior-level PHP and Laravel skills.

## Features
- User authentication (Laravel Sanctum)
- Shopping list CRUD with ownership validation
- Add, update, and delete shopping list items (with immutable pricing)
- View all groceries with selection status
- Currency formatting using SOLID principles (GBP default)
- Subtotal calculation and storage on shopping lists
- Comprehensive feature tests and static analysis (PHPStan Level 8)

## Getting Started

### Prerequisites
- Docker & Docker Compose
- Composer

### Setup
1. **Clone the repository:**
   ```bash
   git clone <repo-url>
   cd backend
   ```
2. **Install dependencies:**
   ```bash
   composer install (using local composer - or run using a composer docker container)
   ```
3. **Copy .env and generate key:**
   ```bash
   cp .env.example .env
   ./vendor/bin/sail artisan key:generate
   ```
4. **Start containers:**
   ```bash
   ./vendor/bin/sail up -d
   ```
5. **Run migrations and seeders:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
6. **Run tests:**
   ```bash
   ./vendor/bin/sail composer test
   ```

### Static Analysis & Formatting
- **PHPStan (Level 8):**
  ```bash
  ./vendor/bin/sail composer analyse
  ```
- **Pint (PSR-12):**
  ```bash
  ./vendor/bin/sail composer format
  ```

## API Overview

- **Authentication:** Laravel Sanctum (token-based)
- **Base URL:** `/api`
- **Key Endpoints:**
  - `POST /api/login` — Authenticate user
  - `GET /api/shopping-list` — List all shopping lists
  - `POST /api/shopping-list` — Create a new shopping list
  - `GET /api/shopping-list/{shopping_list}/items` — View groceries with selection status
  - `POST /api/shopping-list/{shopping_list}/items` — Add/update items
  - `DELETE /api/shopping-list/{shopping_list}/items/{slug}` — Remove item by grocery slug

## Project Structure
- `app/Http/Controllers` — Thin controllers
- `app/Http/Requests` — Form Requests for validation
- `app/Http/Resources` — API Resources for JSON
- `app/Models` — Eloquent models
- `app/Services` — Business logic (e.g., ShoppingListItemService)
- `app/Contracts` — Money formatting interface
- `database/migrations` — Database schema
- `database/seeders` — Seed data (20 groceries)
- `tests/Feature` — Feature tests

## Quality Gates
- PHPStan Level 8 passes (`composer analyse`)
- Pint passes (`composer format`)
- All tests pass (`composer test`)
- Ownership validation implemented
- Currency handled as integers
- Form Requests used for validation
- API Resources used for responses

## Review Notes
- All business logic is in services, not controllers
- Ownership and security enforced at all layers
- Money formatting is SOLID and extensible
- Subtotal is stored and tested for accuracy
- Feature tests cover all critical flows (add, update, delete, subtotal)

---

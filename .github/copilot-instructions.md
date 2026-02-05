# AI Coding Agent Instructions for Peminjaman Alat

## Project Overview

**Peminjaman Alat** is a tool rental/equipment loan management system built with Laravel 12 and Breeze authentication. It features role-based access control with regular users and admin roles, using Tailwind CSS for styling and Vite for bundling.

**Tech Stack:**

- Laravel 12 (Framework)
- Laravel Breeze (Authentication scaffolding)
- Pest 4 (Testing)
- Tailwind CSS 4 + Alpine.js (Frontend)
- Vite (Asset bundling)
- PostgreSQL/MySQL (Database via migrations)

## Critical Architecture Patterns

### 1. Role-Based Access Control

The application implements role-based authorization via the `role` column in the `users` table (default: 'user', possible values: 'user', 'petugas', 'admin'):

- **Admin Middleware** ([App\Http\Middleware\Admin](app/Http/Middleware/Admin.php)): Checks `$user->role === 'admin'`, redirects to `admin.login` if not authorized
- **Petugas Middleware** ([App\Http\Middleware\Petugas](app/Http/Middleware/Petugas.php)): Checks `$user->role === 'petugas'`, redirects to `petugas.login` if not authorized
- **User Model** includes `isAdmin()` and `isPetugas()` methods for role checking
- Route structure:
    - Regular users: `/dashboard` (via Breeze)
    - Admins: `/admin/login` → `/admin/dashboard`
    - Petugas (Officers): `/petugas/login` → `/petugas/dashboard`

**Related files:**

- [app/Http/Middleware/Admin.php](app/Http/Middleware/Admin.php) — Admin authorization
- [app/Http/Middleware/Petugas.php](app/Http/Middleware/Petugas.php) — Petugas authorization
- [app/Models/User.php](app/Models/User.php) — User model with role support
- [routes/web.php](routes/web.php) — Route definitions with middleware groups

### 2. Authentication Flow

- **User Registration:** Via Breeze (auto-generated routes in [routes/auth.php](routes/auth.php))
- **Admin Registration:** Only admins can create users (User/Petugas/Admin) via `/admin/register` → [App\Http\Controllers\Admin\UserController@store](app/Http/Controllers/Admin/UserController.php)
- **Admin Login:** Separate endpoint at `/admin/login` via [App\Http\Controllers\Auth\AdminAuthController](app/Http/Controllers/Auth/AdminAuthController.php)
- **Petugas Login:** Separate endpoint at `/petugas/login` via [App\Http\Controllers\Auth\PetugasAuthController](app/Http/Controllers/Auth/PetugasAuthController.php)
- **Session Management:** Laravel's session-based authentication with remember token support
- **Login Redirect Logic:** If petugas tries to login via admin portal, auto-redirects to petugas login page

### 3. Database Schema

Core tables managed via migrations in [database/migrations/](database/migrations/):

- `users`: id, name, email, password, role (default 'user', values: 'user'/'petugas'/'admin'), email_verified_at, remember_token, timestamps
- `password_reset_tokens`: email, token, created_at
- `sessions`: id, user_id, ip_address, user_agent, payload, last_activity

**Key Pattern:** Migrations use `up()` and `down()` methods following Laravel 12 style. When modifying columns, include ALL previously defined attributes to prevent silent data loss.

### 4. Frontend & Styling

- **View Structure:** Blade templates in [resources/views/](resources/views/) organized by section (admin/, auth/, components/, layouts/, profile/)
- **Layout Component:** [x-app-layout](resources/views/layouts/app.blade.php) wraps pages with header slots
- **Styling:** Tailwind CSS v4 with Alpine.js for interactivity; use existing utility classes before creating new ones
- **Build:** Vite handles bundling; CSS from [resources/css/app.css](resources/css/app.css), JS from [resources/js/app.js](resources/js/app.js)
- **Bundling Issues:** If styles/scripts don't appear, instruct user to run `npm run dev` (watch mode) or `npm run build` (production)

## Developer Workflows

### Testing

Run tests using **Pest 4** (configured in [phpunit.xml](phpunit.xml)):

```bash
php artisan test --compact                          # Run all tests
php artisan test --compact --filter=testName       # Run specific test
php artisan make:test --pest {name}                # Create feature test
php artisan make:test --pest --unit {name}         # Create unit test
```

- Default: Feature tests (test full request cycles)
- Unit tests: For testing isolated classes/methods
- Tests use [database/factories/UserFactory.php](database/factories/UserFactory.php) for test data
- Check factory custom states before manually setting up test data

### Code Formatting

- **Pint** (Laravel code formatter) ensures style consistency:
    ```bash
    vendor/bin/pint --dirty    # Fix only modified files
    vendor/bin/pint            # Fix all files
    ```
- Run before finalizing changes; do NOT use `--test` flag

### Building & Running

```bash
npm run dev                 # Start Vite in watch mode (frontend changes auto-reload)
npm run build              # Production build
composer run dev           # Runs npm run dev + Laravel dev server
php artisan serve          # Start Laravel development server
php artisan migrate        # Run pending database migrations
php artisan tinker         # Interactive PHP shell for debugging
```

## Code Conventions

### PHP & Laravel Patterns

1. **Constructors:** Use PHP 8 property promotion; avoid empty constructors

    ```php
    public function __construct(public UserRepository $users) { }
    ```

2. **Type Declarations:** Always use explicit return types and parameter type hints

    ```php
    protected function isAccessible(User $user, ?string $path = null): bool { ... }
    ```

3. **Control Structures:** Always use curly braces, even for single lines

    ```php
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    ```

4. **Comments:** Prefer PHPDoc blocks; avoid inline comments unless logic is exceptional

    ```php
    /**
     * Determine if the user is an administrator.
     * @return bool
     */
    public function isAdmin(): bool { ... }
    ```

5. **Eloquent Over Raw SQL:**
    - Use model relationships with return type hints
    - Eager-load related data to prevent N+1 queries: `User::with('roles')->get()`
    - Avoid `DB::` queries; use `Model::query()` instead

6. **Controllers:** Create Form Request classes for validation, never inline validation
    - Example: Check [app/Http/Controllers/Admin/UserController.php](app/Http/Controllers/Admin/UserController.php) for inline validation patterns (current approach); new controllers should use Form Request classes

7. **Named Routes:** Always use `route()` function and named routes in web.php

    ```php
    Route::get('register', [...UserController::class, 'create'])->name('admin.register');
    // In views/controllers: redirect()->route('admin.register')
    ```

8. **Configuration:** Use `config()` function, never `env()` outside config files

    ```php
    // ✓ Correct: In code
    $appName = config('app.name');

    // ✗ Wrong: Never use env() in controllers/models
    $appName = env('APP_NAME');
    ```

### File Organization

- **Models:** [app/Models/](app/Models/) — Eloquent models with relationships, casts, factories
- **Controllers:** [app/Http/Controllers/](app/Http/Controllers/) — organized by domain (Admin/, Auth/)
- **Middleware:** [app/Http/Middleware/](app/Http/Middleware/) — request authorization/inspection
- **Migrations:** [database/migrations/](database/migrations/) — schema changes with numeric timestamps
- **Factories:** [database/factories/](database/factories/) — test data generators
- **Views:** [resources/views/](resources/views/) — Blade templates by feature/section

### View Patterns

- Use Blade components: `<x-component-name />`
- Slot-based layouts: `<x-slot name="header">` in app-layout
- Localization strings: `{{ __('key') }}` for i18n support
- Links via named routes: `route('route.name')` in href attributes

## Common Tasks

### Adding a New Admin Feature

1. Create controller: `php artisan make:controller Admin/FeatureController --no-interaction`
2. Add routes under `Route::prefix('admin')` in [routes/web.php](routes/web.php)
3. Create Blade view in `resources/views/admin/feature.blade.php`
4. Wrap with `<x-app-layout>` component; use `<x-slot name="header">` for titles
5. Use Tailwind utilities for styling; follow existing dashboard patterns

### Adding a New Model with Database Support

1. Create migration: `php artisan make:migration create_tablename_table`
2. Create model + factory: `php artisan make:model ModelName --no-interaction` (or add `--factory`)
3. Define relationships with return type hints in model
4. Run: `php artisan migrate`
5. Add tests in `tests/Feature/` or `tests/Unit/`

### Testing a Feature

1. Create test: `php artisan make:test --pest FeatureName`
2. Use `$this->faker` or `fake()` for test data (follow existing convention)
3. Use factories: `User::factory()->create()`
4. Assert responses: `$response->assertStatus(200)`, `$response->assertRedirectToRoute('name')`
5. Run: `php artisan test --compact --filter=FeatureName`

## Integration & Dependencies

### External Packages

- **Laravel Breeze:** Provides auth scaffolding; customizable views in `resources/views/auth/`
- **Laravel Tinker:** REPL for testing queries/code: `php artisan tinker`
- **Faker:** Generate fake data in tests/seeders via `fake()` or `$this->faker`
- **Mockery:** Mock dependencies in tests

### Configuration Files

- [config/app.php](config/app.php) — App name, locale, timezone, providers
- [config/auth.php](config/auth.php) — Auth guards, user providers (uses sessions by default)
- [config/database.php](config/database.php) — DB connection (check .env for credentials)
- [bootstrap/app.php](bootstrap/app.php) — Laravel 12 setup: routing, middleware config, exception handling (NO app/Http/Kernel.php in v12)

### Service Providers

- [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php) — Register custom bindings, boot logic
- Auto-loaded via [bootstrap/providers.php](bootstrap/providers.php) (Laravel 12 style)

## Key Files at a Glance

| File                                                                                                                         | Purpose                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| [routes/web.php](routes/web.php)                                                                                             | Route definitions with middleware groups |
| [app/Models/User.php](app/Models/User.php)                                                                                   | User model with role support             |
| [app/Http/Middleware/Admin.php](app/Http/Middleware/Admin.php)                                                               | Admin authorization check                |
| [database/migrations/0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php) | Users table schema with role column      |
| [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)                                               | Main layout component                    |
| [resources/css/app.css](resources/css/app.css)                                                                               | Tailwind CSS directives                  |
| [vite.config.js](vite.config.js)                                                                                             | Vite bundling config                     |
| [tailwind.config.js](tailwind.config.js)                                                                                     | Tailwind theme customization             |
| [phpunit.xml](phpunit.xml)                                                                                                   | Pest/PHPUnit configuration               |

## Important Reminders

1. **Always use Laravel's `make:` commands** — They scaffold correct structure and namespacing
2. **Test-driven approach:** Write tests before or alongside features; Pest is configured
3. **Middleware registration:** Use `bootstrap/app.php` (NOT `app/Http/Kernel.php` which doesn't exist in v12)
4. **Role checking:** Use Admin middleware for restricted routes; check `User::isAdmin()` in code
5. **Asset bundling:** Run `npm run dev` or `npm run build` after frontend changes
6. **Code formatting:** Always run `vendor/bin/pint --dirty` before committing
7. **N+1 prevention:** Eager-load relationships: `User::with('roles', 'permissions')->get()`
8. **No raw SQL:** Prefer Eloquent; use query builder only for complex operations

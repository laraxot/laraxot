# TDD con Laravel e Pest - Guida Laraxot

## Scopo

Guida al Test-Driven Development (TDD) adattata all'architettura Laraxot: moduli nwidart, Pest, Filament/XotBase, `.env.testing` SQLite, `DatabaseTransactions`.

## Ciclo RED-GREEN-REFACTOR

```
RED → Verifica fallimento → GREEN → Verifica passaggio → REFACTOR → Ripeti
```

1. **RED**: Scrivi un test che fallisce (comportamento desiderato)
2. **Verifica RED**: Esegui il test e conferma che fallisce per il motivo giusto
3. **GREEN**: Scrivi il minimo codice per far passare il test
4. **Verifica GREEN**: Esegui il test e conferma che passa
5. **REFACTOR**: Migliora il codice mantenendo i test verdi

## Regole Critiche Laraxot

- **MAI** `RefreshDatabase` — usare `DatabaseTransactions` e `.env.testing` SQLite in-memory
- **Sempre** Pest: `it()`, `test()`, `describe()` — no classi PHPUnit
- **Sempre** `uses(\Modules\Xot\Tests\TestCase::class)` o TestCase del modulo
- **Sempre** mock `XotData` in `beforeEach()` per test che usano User/dipendenze
- **Sempre** estendere XotBase (mai Filament direttamente)

## Configurazione .env.testing

```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
```

## Esempio TDD: Feature Test

```php
<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

describe('Task Management', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can create a new task', function () {
        $taskData = [
            'title' => 'Complete TDD guide',
            'description' => 'Write comprehensive TDD documentation',
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', [
            'title' => $taskData['title'],
            'user_id' => $this->user->id,
        ]);
    });

    it('validates required fields when creating a task', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });
});
```

## Esempio TDD: Filament Resource

```php
<?php

declare(strict_types=1);

use Modules\User\Filament\Resources\UserResource\Pages\ListUsers;
use Modules\User\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
});

it('can list users', function () {
    $users = User::factory()->count(3)->create();

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can create user', function () {
    Livewire::test(\Modules\User\Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->call('create')
        ->assertHasNoErrors();
});
```

## Esempio TDD: Widget Livewire

```php
<?php

declare(strict_types=1);

use Livewire\Livewire;
use Modules\Cms\Filament\Widgets\Auth\LoginWidget;

uses(\Modules\Xot\Tests\TestCase::class);

beforeEach(function () {
    mockXotData(); // Obbligatorio per widget con dipendenze User
});

it('renders login widget', function () {
    Livewire::test(LoginWidget::class)
        ->assertStatus(200);
});
```

## Comandi

```bash
# Eseguire test singolo
php artisan test --filter="can create a new task"

# Eseguire test modulo
php artisan test Modules/Activity/

# Eseguire con output compatto
php artisan test --compact
```

## Riferimenti

- [testing_database_rule](testing_database_rule.md)
- [Modules/Xot/docs/testing/testing-best-practices.md](../../laravel/Modules/Xot/docs/testing/testing-best-practices.md)
- [Modules/Xot/docs/testing/tdd-workflow.md](../../laravel/Modules/Xot/docs/testing/tdd-workflow.md)
- [Laracasts: Build a Laravel App with TDD](https://laracasts.com/series/build-a-laravel-app-with-tdd)
- [Filament: How to write tests for Filament admin panels](https://filamentphp.com/content/leandrocfe-how-to-write-tests-for-filament-admin-panels)
- [Pest Installation](https://pestphp.com/docs/installation)

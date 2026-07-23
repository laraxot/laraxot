# Guida Completa al Test-Driven Development (TDD) con Laravel e Pest

## Indice

1. [Introduzione al TDD](#introduzione-al-tdd)
2. [Il Ciclo Red-Green-Refactor](#il-ciclo-red-green-refactor)
3. [Configurazione dell'Ambiente di Test](#configurazione-dellambiente-di-test)
4. [Struttura dei Test](#struttura-dei-test)
5. [Pest PHP: Sintassi e Pattern](#pest-php-sintassi-e-pattern)
6. [Testing dei Modelli](#testing-dei-modelli)
7. [Testing delle Azioni](#testing-delle-azioni)
8. [Testing dei Filament Resources](#testing-dei-filament-resources)
9. [Testing delle API](#testing-delle-api)
10. [Mocking e Dependency Injection](#mocking-e-dependency-injection)
11. [Best Practices](#best-practices)
12. [Risorse Utili](#risorse-utili)

---

## Introduzione al TDD

Il Test-Driven Development (TDD) è una metodologia di sviluppo che enfatizza la scrittura dei test prima del codice di implementazione. In Laravel, questa pratica si integra perfettamente con l'architettura del framework, grazie a strumenti come Pest, Factories, e le convenzioni di testing integrate.

### Perché TDD?

- **Qualità del codice**: Il codice è progettato per essere testabile
- **Rifattorizzazione sicura**: I test esistenti garantiscono che le modifiche non rompano funzionalità esistenti
- **Documentazione vivente**: I test descrivono il comportamento atteso del sistema
- **Debug più rapido**: I test falliti indicano esattamente dove si trova il problema

---

## Il Ciclo Red-Green-Refactor

Il TDD si basa su un ciclo di tre fasi:

### 🔴 Fase 1: RED - Scrivi un test che fallisce

Scrivi il test che descrive il comportamento desiderato. Il test deve fallire perché il codice non esiste ancora.

```php
it('creates a user with valid data', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'name', 'email']
        ]);
});
```

### 🟢 Fase 2: GREEN - Scrivi il codice minimo per far passare il test

Scrivi la minima quantità di codice necessaria per far passare il test. Non ottimizzare ancora.

```php
// Route minima
Route::post('/api/users', function (Request $request) {
    return response()->json([
        'data' => [
            'id' => 1,
            'name' => $request->name,
            'email' => $request->email
        ]
    ], 201);
});
```

### 🔵 Fase 3: REFACTOR - Migliora il codice

Ora che i test passano, puoi rifattorizzare il codice per migliorarlo mantenendo i test verdi.

```php
// Rifattorizzazione: estrai logica in un Controller
class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        
        return response()->json([
            'data' => new UserResource($user)
        ], 201);
    }
}
```

---

## Configurazione dell'Ambiente di Test

### Installazione di Pest

```bash
composer require pestphp/pest --dev --with-all-dependencies
composer require pestphp/pest-plugin-laravel --dev
php artisan pest:install
```

### Configurazione .env.testing

**.env.testing** deve essere una copia IDENTICA di .env con SOLO "_test" aggiunto ai nomi dei database:

```bash
# .env
DB_DATABASE=laravelpizza_data

# .env.testing (CORRETTO)
DB_DATABASE=laravelpizza_data_test
```

**Regole fondamentali:**
- APP_URL deve essere IDENTICO a .env
- Tutte le variabili TRANNE i database DEVONO essere IDENTICHE
- NON creare nuove variabili come `DB_DATABASE_ACTIVITY`

### Configurazione phpunit.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
    </php>
</phpunit>
```

---

## Struttura dei Test

### Organizzazione Directory

```
tests/
├── Feature/                   # Test di integrazione
│   ├── AuthenticationTest.php
│   ├── UserManagementTest.php
│   └── Api/
│       └── UsersApiTest.php
├── Unit/                     # Test unitari
│   ├── Actions/
│   │   └── CreateUserActionTest.php
│   └── Models/
│       └── UserModelTest.php
└── Pest.php                  # Configurazione globale
```

### File TestCase Base

```php
// tests/Pest.php
<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class)->in('Feature', 'Unit');
```

**Importante:** Usa `DatabaseTransactions` invece di `RefreshDatabase` per performance migliori.

---

## Pest PHP: Sintassi e Pattern

### Sintassi Base

```php
// Test semplice
it('does something', function () {
    expect(true)->toBeTrue();
});

// Test con descrizione
describe('User Management', function () {
    it('can create a user', function () {
        $user = User::factory()->create();
        expect($user)->toBeInstanceOf(User::class);
    });
});
```

### Assertions Comuni

```php
// Assertions di valore
expect($value)->toBe(10);
expect($value)->toEqual([1, 2, 3]);
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();
expect($value)->toBeInstanceOf(User::class);

// Assertions di raccolta
expect($users)->toHaveCount(3);
expect($users)->toContain($user);
expect($collection->first()->name)->toBe('John');

// Assertions Laravel
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);
$this->assertDatabaseMissing('users', ['email' => 'nonexistent@example.com']);
$this->assertDatabaseCount('users', 5);
```

### Datasets

```php
it('validates email format', function (string $email, bool $isValid) {
    $user = User::factory()->make(['email' => $email]);
    // Test validazione
})->with([
    'valid email' => ['user@example.com', true],
    'invalid email' => ['not-an-email', false],
]);
```

---

## Testing dei Modelli

### Model Factory

```php
// database/factories/UserFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    // State per utente admin
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }

    // State per utente verificato
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }
}
```

### Test del Modello

```php
describe('User Model', function () {
    it('creates a user with factory', function () {
        $user = User::factory()->create();

        expect($user)->toBeInstanceOf(User::class);
        expect($user->email)->toBeValidEmail();
    });

    it('generates avatar URL', function () {
        $user = User::factory()->create(['name' => 'John Doe']);

        expect($user->avatar_url)
            ->toContain('John+Doe')
            ->toContain('gravatar.com');
    });

    it('has many posts', function () {
        $user = User::factory()
            ->has(Post::factory()->count(3))
            ->create();

        expect($user->posts)->toHaveCount(3);
    });
});
```

---

## Testing delle Azioni

Le Azioni sono il cuore della logica di dominio e devono essere testate in isolamento.

### Struttura Action

```php
<?php

namespace App\Actions\User;

use App\Data\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function execute(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => bcrypt($data->password),
            ]);

            // Logica aggiuntiva (es. invio email benvenuto)
            // event(new UserRegistered($user));

            return $user;
        });
    }
}
```

### Test dell'Azione

```php
use App\Actions\User\CreateUserAction;
use App\Data\CreateUserData;
use function Pest\Laravel\assertDatabaseHas;

it('creates a user successfully', function () {
    // Arrange
    $data = new CreateUserData(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'password123'
    );

    // Act
    $user = resolve(CreateUserAction::class)->execute($data);

    // Assert
    expect($user)
        ->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');

    assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

it('throws exception for duplicate email', function () {
    // Arrange
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $data = new CreateUserData(
        name: 'Jane Doe',
        email: 'existing@example.com',
        password: 'password123'
    );

    // Act & Assert
    expect(fn () => resolve(CreateUserAction::class)->execute($data))
        ->toThrow(\Exception::class, 'Email already exists');
});
```

---

## Testing dei Filament Resources

Filament fornisce helper specifici per il testing basati su Livewire.

### Test Resource List

```php
use App\Filament\Resources\UserResource;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can list users', function () {
    $users = User::factory()->count(10)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->assertCountTableRecords(10);
});

it('can search users by name', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable('John')
        ->assertCanSeeTableRecords(User::where('name', 'like', '%John%')->get())
        ->assertCantSeeTableRecords(User::where('name', 'like', '%Jane%')->get());
});
```

### Test Resource Create

```php
use App\Filament\Resources\UserResource;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can create user', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
});

it('validates required fields', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'required']);
});
```

### Test Resource Edit

```php
use App\Filament\Resources\UserResource;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can update user', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm(['name' => 'New Name'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->name)->toBe('New Name');
});

it('can delete user', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
        ->callPageAction('delete')
        ->assertRedirected();

    expect(User::find($user->id))->toBeNull();
});
```

### Test Actions

```php
use App\Filament\Resources\UserResource;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can export users', function () {
    User::factory()->count(5)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableAction('export')
        ->assertDownloaded();
});

it('can bulk delete users', function () {
    $users = User::factory()->count(3)->create();
    $userIds = $users->map(fn ($u) => $u->id)->toArray();

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableBulkAction('delete', $userIds);

    expect(User::whereIn('id', $userIds)->exists())->toBeFalse();
});
```

---

## Testing delle API

### Feature Test API

```php
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

describe('API Users', function () {
    it('returns unauthorized for guest', function () {
        $this->getJson('/api/users')
            ->assertStatus(401);
    });

    it('can create user when authenticated', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->postJson('/api/users', [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => 'password123',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'created_at']
            ]);
    });

    it('validates email uniqueness', function () {
        $existingUser = User::factory()->create(['email' => 'exists@example.com']);
        $user = User::factory()->create();

        actingAs($user)
            ->postJson('/api/users', [
                'name' => 'Test',
                'email' => 'exists@example.com',
                'password' => 'password123',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });
});
```

---

## Mocking e Dependency Injection

### Mocking delle Azioni

```php
use function Pest\Laravel\mock;
use function Pest\Laravel\swap;
use App\Actions\Notifications\SendWelcomeEmailAction;

it('sends welcome email on registration', function () {
    // Arrange
    $mockAction = mock(SendWelcomeEmailAction::class);
    $mockAction->shouldReceive('execute')
        ->once()
        ->withArgs(fn (User $user) => $user->email === 'john@example.com');

    swap(SendWelcomeEmailAction::class, $mockAction);

    // Act
    $user = resolve(RegisterUserAction::class)->execute(
        new RegisterUserData(
            name: 'John',
            email: 'john@example.com',
            password: 'password123'
        )
    );

    // Assert
    expect($user)->toBeInstanceOf(User::class);
});
```

### Mocking Facades

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

it('sends welcome email', function () {
    Mail::fake();

    $user = resolve(RegisterUserAction::class)->execute($data);

    Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('does not send email if registration fails', function () {
    Mail::fake();

    try {
        resolve(RegisterUserAction::class)->execute($invalidData);
    } catch (\Exception $e) {
        // Expected
    }

    Mail::assertNothingSent();
});
```

### Mocking Code Esterno

**Regola:** Mocka solo ciò che possiedi. Per servizi esterni, crea un'astrazione.

```php
// ❌ NON fare: mock servizi esterni direttamente
$stripe = mock(\Stripe\StripeClient::class);

// ✅ CORRETTO: crea un'astrazione
// PaymentGateway.php (interfaccia)
interface PaymentGateway
{
    public function charge(int $amount, string $currency): PaymentResult;
}

// NullPaymentGateway.php (per testing)
class NullPaymentGateway implements PaymentGateway
{
    public function charge(int $amount, string $currency): PaymentResult
    {
        return new PaymentResult(success: true, transactionId: 'test_123');
    }
}

// In test
it('processes payment', function () {
    Config::set('services.payment.driver', 'null');
    
    $result = resolve(ProcessPaymentAction::class)->execute(1000, 'eur');
    
    expect($result->isSuccessful())->toBeTrue();
});
```

---

## Best Practices

### ✅ Fai Questo

- **Segui il pattern AAA**: Arrange, Act, Assert
- **Usa factories** per tutti i dati di test
- **Crea metodi dichiarativi nelle factory**: `User::factory()->admin()->verified()`
- **Testa le azioni in isolamento** (unit test)
- **Mocka ciò che possiedi** (azioni, servizi che controlli)
- **Crea astrazioni** per servizi esterni
- **Testa il comportamento**, non l'implementazione
- **Mantieni i test semplici** - un concetto per test
- **Usa DTO test factories** per dati consistenti

### ❌ Non Fare Questo

- **Mockare librerie esterne** - crea uno strato di astrazione
- **Dati hardcoded nei test** - usa sempre le factories
- **Perdere dettagli del database nei test** - usa metodi dichiarativi
- **Testare dettagli implementativi** - testa il comportamento
- **Creare test fragili** - troppi mock, troppo specifici
- **Saltare le factories** - usa sempre factories per i modelli
- **Mescolare arrange e act** - tienili separati
- **Over-mocking** - usa istanze reali quando possibile

### Comandi Utili

```bash
# Esegui tutti i test
php artisan test

# Test con coverage
php artisan test --coverage

# Test paralleli (veloce!)
php artisan test --parallel

# Test specifico
php artisan test --filter="test_name"

# Test con verbose output
php artisan test --verbose

# Esegui solo test di un modulo
./vendor/bin/pest Modules/NomeModulo/tests
```

---

## Risorse Utili

### Documentazione Ufficiale
- [Laravel Testing](https://laravel.com/docs/testing)
- [Pest PHP](https://pestphp.com/docs)
- [Filament Testing](https://filamentphp.com/docs/3.x/admin/testing)

### Tutorial Consigliati
- [Laracasts: Let's Build A Forum With Laravel](https://laracasts.com/series/lets-build-a-forum-with-laravel)
- [Test-Driven Laravel](https://course.testdrivenlaravel.com/)
- [Filament TDD Example](https://github.com/leandrocfe/filament-tdd-example)

### Best Practices
- **Red-Green-Refactor**: Scrivi test prima, implementa dopo
- **Test piccoli e focalizzati**: Un test = un comportamento
- **Feedback loop veloce**: Usa `--parallel` per test più rapidi
- **Coverage significativo**: 100% coverage non significa codice privo di bug

---

## Note Specifiche per Laraxot

### Regole Criticali

1. **MAI usare `RefreshDatabase`** - Usa `DatabaseTransactions`
2. **Test nei moduli**: `Modules/{Module}/tests/`
3. **Estendi XotBase classes**: I test devono usare le classi base del modulo
4. **PHPStan compliance**: Tutti i test devono passare PHPStan level max

### Configurazione Module TestCase

```php
// Modules/ModuleName/tests/TestCase.php
<?php

namespace Modules\ModuleName\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Xot\Providers\XotServiceProvider;
use Modules\ModuleName\Providers\ModuleNameServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            XotServiceProvider::class,
            ModuleNameServiceProvider::class,
        ];
    }
}
```

---

*Ultimo aggiornamento: Febbraio 2026*

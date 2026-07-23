# Standard Testing Pest PHP

## 🚨 REGOLA CRITICA - TUTTI I TESTS DEVONO USARE PEST

**OGNI** test in questo progetto **DEVE** essere scritto usando la sintassi Pest PHP.

### ✅ Cosa DEVI Fare

1. **Scrivere tutti i nuovi tests in sintassi Pest**
2. **Convertire i tests PHPUnit esistenti in Pest**
3. **Usare la sintassi espressiva di Pest**: `test()`, `it()`, `describe()`, `expect()`
4. **Seguire le best practices Pest**: Higher-order tests, datasets, custom expectations

### ❌ Cosa NON DEVI Fare

- ❌ Scrivere tests usando sintassi PHPUnit class-based
- ❌ Usare `class SomeTest extends TestCase`
- ❌ Usare `public function testSomething()`
- ❌ Usare annotazioni `@test`
- ❌ Lasciare tests in stile PHPUnit senza convertirli

## 📖 Riferimento Sintassi Pest

### Struttura Base Test

#### ❌ PHPUnit (SBAGLIATO)
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_can_create_user()
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->id);
        $this->assertEquals('test@example.com', $user->email);
    }
}
```

#### ✅ Pest (CORRETTO)
```php
<?php

use Modules\User\Models\User;

test('it can create user', function () {
    $user = User::factory()->create();
    
    expect($user->id)->not->toBeNull();
    expect($user->email)->toBe('test@example.com');
});

it('has name', function () {
    $user = User::factory()->create(['name' => 'John']);
    
    expect($user->name)->toBe('John');
});
```

### Setup e Teardown

#### ❌ PHPUnit (SBAGLIATO)
```php
class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
```

#### ✅ Pest (CORRETTO)
```php
beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->delete();
});
```

### Tests Raggruppati

#### ✅ Pest (CORRETTO)
```php
describe('User Authentication', function () {
    it('can login', function () {
        // test logic
    });
    
    it('can logout', function () {
        // test logic
    });
    
    it('can register', function () {
        // test logic
    });
});
```

### Expectations (Aspettative)

#### ❌ PHPUnit Assertions (SBAGLIATO)
```php
$this->assertTrue($condition);
$this->assertEquals($expected, $actual);
$this->assertNotNull($value);
```

#### ✅ Pest Expectations (CORRETTO)
```php
expect($condition)->toBeTrue();
expect($actual)->toBe($expected);
expect($value)->not->toBeNull();
```

## 🔄 Workflow di Conversione

Quando trovi un test PHPUnit:

1. **Identifica il file test**
2. **Crea lock file**: `TestFile.php.lock`
3. **Converti in sintassi Pest**:
   - Rimuovi dichiarazione classe
   - Converti metodi in funzioni `test()` o `it()`
   - Sostituisci assertions con expectations
   - Converti setUp/tearDown in beforeEach/afterEach
4. **Verifica conversione**:
   - Esegui test: `php artisan test --filter=TestName`
   - PHPStan Level 10
   - PHPMD
   - PHPInsights
5. **Cancella lock file**
6. **Aggiorna docs modulo**
7. **Git commit**

## 📋 Checklist Conversione

- [ ] Rimuovi `class XTest extends TestCase`
- [ ] Rimuovi metodi `public function test*()`
- [ ] Rimuovi annotazioni `@test`
- [ ] Converti in funzioni `test()` o `it()`
- [ ] Sostituisci `$this->assert*()` con `expect()`
- [ ] Converti `setUp()` in `beforeEach()`
- [ ] Converti `tearDown()` in `afterEach()`
- [ ] Usa `describe()` per raggruppamenti logici
- [ ] Aggiungi datasets dove applicabile
- [ ] Esegui e verifica che i tests passino
- [ ] Conformità PHPStan Level 10
- [ ] Aggiorna documentazione

## 🎯 Perché Pest?

1. **Leggibilità**: Sintassi più espressiva e leggibile
2. **Meno Boilerplate**: Niente classi, meno codice
3. **Feature Moderne**: Datasets, higher-order tests, custom expectations
4. **Migliore DX**: Output più chiaro, messaggi di errore migliori
5. **Integrazione Laravel**: Costruito specificamente per Laravel/PHP
6. **Community**: Ecosistema in crescita e supporto plugin

## 🚫 Tolleranza Zero

**NESSUN test in stile PHPUnit è permesso in questo codebase.**

Se ne trovi:
1. Convertili immediatamente
2. Documenta la conversione nelle docs del modulo
3. Commit con messaggio chiaro sulla conversione Pest

## 🔗 Filosofia Laraxot

Questo standard incarna i principi Laraxot:

- **Logic**: Sintassi matematicamente più pulita
- **Philosophy**: DRY - meno codice ripetitivo
- **Politics**: Standard centralizzato per tutti i tests
- **Religion**: Pest come dogma testing
- **Zen**: La semplicità è la massima sofisticazione

---

**Ultimo Aggiornamento**: 15 Dicembre 2025
**Status**: OBBLIGATORIO - NESSUNA ECCEZIONE
**Framework**: Pest PHP v2+

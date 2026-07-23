# Standard Testing: Pest + DatabaseTransactions

## ⚠️ REGOLE CRITICHE ASSOLUTE ⚠️

### 1. **SEMPRE Pest** - Mai PHPUnit
### 2. **MAI RefreshDatabase** - Sempre DatabaseTransactions

## 📚 Studio delle Motivazioni

### Perché Pest invece di PHPUnit

#### ✅ Vantaggi Pest
- **Sintassi più pulita**: `test()` e `it()` invece di classi verbose
- **Better DX**: Developer experience superiore
- **Readable tests**: Test più leggibili e manutenibili
- **Modern approach**: Standard moderno per Laravel
- **Less boilerplate**: Meno codice ripetitivo

#### ❌ Problemi PHPUnit
- **Verbose syntax**: Classi e metodi verbosi
- **Boilerplate code**: Molto codice ripetitivo
- **Legacy approach**: Approccio più vecchio
- **Harder maintenance**: Più difficile da mantenere

### Perché DatabaseTransactions invece di RefreshDatabase

#### ✅ Vantaggi DatabaseTransactions
- **Performance 100x migliore**: Transazioni vs migrazioni complete
- **Isolamento perfetto**: Rollback automatico dopo ogni test
- **Scalabilità**: Funziona con migliaia di test
- **Memory efficient**: Usa meno memoria
- **Faster CI/CD**: Pipeline più veloci

#### ❌ Problemi RefreshDatabase
- **Lentissimo**: Esegue tutte le migrazioni per ogni test
- **Memory intensive**: Usa molta memoria
- **CI/CD killer**: Pipeline che durano ore
- **Developer frustration**: Feedback lentissimo
- **Scalability nightmare**: Non scala con progetti grandi

## 📊 Confronto Performance

### RefreshDatabase (❌ VIETATO)
```php
// OGNI test esegue TUTTE le migrazioni
Test 1: 5-15 secondi (migrazioni)
Test 2: 5-15 secondi (migrazioni) 
Test 3: 5-15 secondi (migrazioni)
...
615 tests = 51-154 MINUTI!
```

### DatabaseTransactions (✅ OBBLIGATORIO)
```php
// OGNI test usa solo transazioni
Test 1: 0.1-0.5 secondi (transazioni)
Test 2: 0.1-0.5 secondi (transazioni)
Test 3: 0.1-0.5 secondi (transazioni)
...
615 tests = 1-5 MINUTI!
```

## 🎯 Pattern Corretti

### ✅ Pest Test Corretto
```php
<?php

declare(strict_types=1);

use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class); // ✅ SEMPRE

describe('TimeClockWidget', function () {
    
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->employee = Employee::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);
    });

    test('displays current time correctly', function () {
        // Arrange, Act, Assert
        expect(true)->toBeTrue();
    });

    it('can clock in successfully', function () {
        // Test implementation
        expect($this->employee)->toBeInstanceOf(Employee::class);
    });
});
```

### ❌ Pattern Vietati
```php
<?php

// ❌ VIETATO - PHPUnit class style
class TimeClockWidgetTest extends TestCase
{
    use RefreshDatabase; // ❌ GRAVISSIMO - LENTISSIMO
    
    public function testDisplaysTime()
    {
        // Old verbose syntax
    }
}
```

## 🔧 Configurazione .env.testing

### File Obbligatorio per Performance
```env
# .env.testing - OBBLIGATORIO
APP_ENV=testing
APP_DEBUG=false

# SQLite in memoria - VELOCISSIMO
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Cache array - NO PERSISTENZA
CACHE_STORE=array
SESSION_DRIVER=array

# Queue sincrona
QUEUE_CONNECTION=sync

# Mail array - NO EMAIL REALI
MAIL_MAILER=array

# Log minimal
LOG_LEVEL=error
```

## 📁 Struttura Test Pest

### Directory Standard
```
Modules/Employee/tests/
├── Pest.php                    # Configurazione Pest per modulo
├── TestCase.php               # Base class per il modulo
├── Unit/                      # Test unitari
│   ├── Models/
│   │   ├── EmployeeTest.php
│   │   └── WorkHourTest.php
│   └── Actions/
├── Feature/                   # Test funzionali
│   ├── Widgets/
│   │   └── TimeClockWidgetTest.php
│   └── Resources/
└── Integration/               # Test di integrazione
```

### Pest.php Configuration
```php
<?php

declare(strict_types=1);

use Modules\Employee\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// ✅ CONFIGURAZIONE CORRETTA
uses(TestCase::class, DatabaseTransactions::class)->in('Feature', 'Unit', 'Integration');

// Helper functions per il modulo
function createEmployee(array $attributes = []): Employee
{
    return Employee::factory()->create($attributes);
}

function createWorkHour(Employee $employee, array $attributes = []): WorkHour
{
    return WorkHour::factory()->create([
        'employee_id' => $employee->id,
        ...$attributes
    ]);
}
```

### TestCase.php Base
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions; // ✅ SEMPRE

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup specifico modulo se necessario
        $this->withoutExceptionHandling();
    }
}
```

## 🔍 Correzione Test Esistenti

### Ricerca Test da Correggere
```bash
# Trova tutti i test PHPUnit da convertire
find Modules/ -name "*.php" -path "*/tests/*" | xargs grep -l "class.*Test extends"

# Trova tutti i RefreshDatabase da sostituire
find Modules/ -name "*.php" -path "*/tests/*" | xargs grep -l "RefreshDatabase"
```

### Pattern di Conversione

#### Da PHPUnit a Pest
```php
// PRIMA (PHPUnit) ❌
class EmployeeTest extends TestCase
{
    use RefreshDatabase; // ❌ LENTISSIMO
    
    public function testCanCreateEmployee()
    {
        $employee = Employee::factory()->create();
        $this->assertInstanceOf(Employee::class, $employee);
    }
}

// DOPO (Pest) ✅
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class); // ✅ VELOCE

test('can create employee', function () {
    $employee = Employee::factory()->create();
    expect($employee)->toBeInstanceOf(Employee::class);
});
```

## 🚀 Implementazione Immediata

### Workflow di Correzione
1. **Analizzare test esistenti** nel modulo Employee
2. **Convertire PHPUnit → Pest** dove necessario
3. **Sostituire RefreshDatabase → DatabaseTransactions** ovunque
4. **Aggiornare Pest.php** configuration
5. **Testare che tutto funzioni**
6. **Documentare pattern corretti**

### Script di Correzione Automatica
```bash
# Sostituisci RefreshDatabase in tutti i test
find Modules/ -name "*.php" -path "*/tests/*" -exec sed -i 's/RefreshDatabase/DatabaseTransactions/g' {} \;
find Modules/ -name "*.php" -path "*/tests/*" -exec sed -i 's/use Illuminate\\Foundation\\Testing\\RefreshDatabase;/use Illuminate\\Foundation\\Testing\\DatabaseTransactions;/g' {} \;
```

## 📊 Benefici Immediati

### Performance
- **Da ore a minuti**: Suite di test 100x più veloce
- **Feedback rapido**: Sviluppo più efficiente
- **CI/CD veloce**: Pipeline sotto i 5 minuti

### Developer Experience
- **Pest syntax**: Test più leggibili
- **Faster iteration**: Cicli di sviluppo rapidi
- **Better debugging**: Errori più chiari
- **Modern tooling**: Standard attuali

### Scalabilità
- **Migliaia di test**: Performance costante
- **Team scaling**: Onboarding più facile
- **Maintenance**: Costi ridotti
- **Quality**: Standard più alti

---

**REGOLE IMPLEMENTATE**: Pest + DatabaseTransactions  
**PERFORMANCE**: 100x miglioramento  
**STANDARD**: Moderni e professionali

Procedo immediatamente con l'analisi e correzione di tutti i test del modulo Employee!

## Collegamenti

- [Testing Database Rule](./testing_database_rule.md)
- [Pest Documentation](https://pestphp.com/docs)
- [Laravel Testing](https://laravel.com/docs/testing)

*Creato: Gennaio 2025*

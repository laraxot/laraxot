# Regola Critica: Database Testing senza RefreshDatabase

## ⚠️ REGOLA ASSOLUTA LARAXOT ⚠️

**MAI usare RefreshDatabase nei test. SEMPRE usare .env.testing con database dedicato.**

## Motivazioni Critiche

### 🐌 Performance Disaster
- **RefreshDatabase**: Esegue TUTTE le migrazioni ad ogni test
- **Con 615+ file di test**: Ore di esecuzione invece di minuti
- **.env.testing + SQLite**: 100x più veloce, test in secondi

### 🔒 Isolamento Ambienti
- **Database separato**: Zero rischio di corrompere dati di sviluppo
- **Configurazioni dedicate**: Ogni ambiente ha le sue impostazioni
- **Transazioni automatiche**: Rollback automatico dopo ogni test

### 🏗️ Filosofia Laraxot
- **Separazione netta**: Dev, test, prod completamente isolati
- **Performance first**: Feedback rapido per sviluppatori
- **Configurazione esplicita**: .env.testing rende tutto chiaro
- **Scalabilità enterprise**: Deve funzionare con migliaia di test

## Pattern Corretto

### ❌ PATTERN VIETATO
```php
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase; // ❌ LENTISSIMO E PERICOLOSO
    
    public function test_something()
    {
        // Test che impiega secondi per le migrazioni
    }
}
```

### ✅ PATTERN OBBLIGATORIO
```php
<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MyTest extends TestCase
{
    use DatabaseTransactions; // ✅ VELOCE E SICURO
    
    public function test_something()
    {
        // Test veloce con rollback automatico
    }
}
```

## Configurazione .env.testing

### File Richiesto
```env
# .env.testing - OBBLIGATORIO
APP_ENV=testing
APP_DEBUG=false

# Database in memoria - VELOCISSIMO
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Cache array - NO PERSISTENZA
CACHE_STORE=array
SESSION_DRIVER=array

# Queue sincrona - TEST IMMEDIATI
QUEUE_CONNECTION=sync

# Mail array - NO EMAIL REALI
MAIL_MAILER=array

# Log minimal - SOLO ERRORI
LOG_LEVEL=error
```

## Correzioni Immediate

### Test Trovati da Correggere
```bash
# Test con RefreshDatabase identificati:
- Modules/Activity/tests/Feature/StoredEventBusinessLogicTest.php
- Modules/Activity/tests/Unit/Models/BaseModelTest.php
- Modules/Activity/tests/Unit/EventSourcingBusinessLogicTest.php
- Modules/Cms/tests/Feature/PageManagementBusinessLogicTest.php
- Modules/Cms/tests/Unit/Models/PageTest.php
- Modules/Cms/tests/Unit/Models/BaseModelTest.php
- E molti altri...
```

### Script di Correzione Automatica
```bash
# Trova tutti i file
find Modules/ -name "*.php" -path "*/tests/*" -exec grep -l "RefreshDatabase" {} \;

# Sostituisci in batch
find Modules/ -name "*.php" -path "*/tests/*" -exec sed -i 's/RefreshDatabase/DatabaseTransactions/g' {} \;
find Modules/ -name "*.php" -path "*/tests/*" -exec sed -i 's/use Illuminate\\Foundation\\Testing\\RefreshDatabase;/use Illuminate\\Foundation\\Testing\\DatabaseTransactions;/g' {} \;
```

## TestCase Base Corretto

### Pattern per Moduli
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
        
        // Setup specifico del modulo se necessario
        $this->artisan('config:clear');
    }
}
```

## Pest Configuration

### Pest.php Corretto
```php
<?php

declare(strict_types=1);

use Modules\Employee\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(TestCase::class, DatabaseTransactions::class)->in('Feature', 'Unit');
```

## Benefici Misurabili

### Prima (RefreshDatabase)
- ⏱️ **Suite completa**: 30-60 minuti
- 🐌 **Singolo test**: 5-15 secondi
- 💾 **Uso memoria**: Alto per migrazioni
- 🔥 **CPU**: Intensivo per DDL operations

### Dopo (.env.testing)
- ⚡ **Suite completa**: 2-5 minuti  
- 🚀 **Singolo test**: 0.1-0.5 secondi
- 💚 **Uso memoria**: Minimo con SQLite
- ❄️ **CPU**: Efficiente con transazioni

## Controlli di Qualità

### Pre-commit Hook
```bash
# Verifica che nessun test usi RefreshDatabase
if grep -r "RefreshDatabase" Modules/*/tests/ --include="*.php"; then
    echo "❌ ERRORE: Test con RefreshDatabase trovati!"
    echo "Correggere prima del commit"
    exit 1
fi
```

### CI/CD Pipeline
```yaml
test:
  script:
    - cp .env.testing .env
    - php artisan test --parallel
  rules:
    - if: '$CI_COMMIT_MESSAGE =~ /RefreshDatabase/'
      when: never  # Blocca commit con RefreshDatabase
```

## Implementazione Immediata

### Checklist Obbligatoria
- [ ] Creare .env.testing con SQLite in memoria
- [ ] Trovare tutti i test con RefreshDatabase  
- [ ] Sostituire con DatabaseTransactions
- [ ] Aggiornare TestCase base di ogni modulo
- [ ] Aggiornare file Pest.php
- [ ] Testare che la suite funzioni
- [ ] Documentare pattern nei docs
- [ ] Aggiungere controlli automatici

### Comando di Verifica
```bash
# Deve restituire 0 risultati dopo la correzione
grep -r "RefreshDatabase" Modules/*/tests/ --include="*.php" | wc -l
```

## Note Tecniche

### SQLite in Memoria
- Database creato fresh per ogni test
- Nessuna persistenza tra test
- Performance native del sistema operativo
- Supporto completo per transazioni

### DatabaseTransactions
- Rollback automatico dopo ogni test
- Isolamento perfetto tra test
- Nessuna pulizia manuale necessaria
- Compatibile con tutti i database driver

---

**IMPLEMENTAZIONE IMMEDIATA RICHIESTA**

Questa regola deve essere applicata SUBITO a tutti i test esistenti prima di procedere con qualsiasi altro sviluppo.

## Database Connections

I moduli usano il database default. NON creare connessioni dedicate per modulo. Vedi [database-connections](../../.cursor/rules/database-connections.mdc).

## Collegamenti

- [TDD Laravel Guide](tdd-laravel-guide.md)
- [Testing Strategy](./testing_strategy.md)
- [Performance Guidelines](../performance/)
- [Laraxot Conventions](../laraxot_conventions.md)

*Ultimo aggiornamento: Gennaio 2025*
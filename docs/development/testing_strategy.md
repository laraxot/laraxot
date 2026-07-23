# Strategia di Testing per TechPlanner

## Principi Fondamentali

### Regola Critica: Priorità Test Esistenti
**PRIMA di creare nuovi test, SEMPRE far funzionare tutti i test esistenti nei moduli.**

### TDD (Test-Driven Development)
Per nuove feature e bugfix: ciclo RED-GREEN-REFACTOR. Vedi [tdd-laravel-guide.md](tdd-laravel-guide.md).

### Workflow Obbligatorio
1. **Analisi Test Esistenti**: Identificare tutti i test presenti nei moduli
2. **Conversione PHPUnit → Pest**: Convertire test PHPUnit legacy in Pest
3. **Risoluzione Errori**: Far funzionare tutti i test esistenti
4. **Validazione Completa**: Tutti i test devono passare
5. **Solo DOPO**: Creare nuovi test per nuove funzionalità

## Stato Attuale dei Test

### Statistiche Generali
- **Totale file di test**: 615
- **Directory Laravel**: `../laravel/`
- **Framework**: Pest (con supporto PHPUnit legacy)

### Moduli con Test Esistenti
```bash
# Moduli identificati con test
- Employee (6 test files)
- Tenant (8 test files) 
- Geo (test files presenti)
- Altri moduli da analizzare...
```

### Tipi di Test Presenti
- **Unit Tests**: Test unitari per modelli e classi
- **Feature Tests**: Test funzionali per business logic
- **Integration Tests**: Test di integrazione tra componenti
- **Performance Tests**: Test di performance specifici

## Struttura Test per Modulo

### Pattern Standard
```
Modules/{ModuleName}/tests/
├── TestCase.php          # Classe base per test del modulo
├── Pest.php             # Configurazione Pest per il modulo
├── Unit/                # Test unitari
│   ├── Models/          # Test per modelli
│   ├── Actions/         # Test per azioni
│   └── ...
├── Feature/             # Test funzionali
├── Integration/         # Test di integrazione
└── Performance/         # Test di performance
```

### Esempi Trovati
- `Employee/tests/Unit/Models/WorkHourTest.php`
- `Employee/tests/Unit/Models/BaseModelTest.php`
- `Employee/tests/Unit/AttendanceTest.php`
- `Tenant/tests/Feature/TenantBusinessLogicTest.php`
- `Tenant/tests/Integration/SushiToJsonIntegrationTest.php`
- `Geo/tests/Feature/AddressIntegrationTest.php`

## Analisi da Completare

### Fase 1: Inventario Completo
```bash
# Comando per analisi completa
find . -name "*.php" -path "*/tests/*" | sort | tee project_docs/development/test_inventory.txt

# Analisi per modulo
find Modules/ -name "*.php" -path "*/tests/*" | cut -d'/' -f2 | sort | uniq -c
```

### Fase 2: Identificazione Framework
```bash
# Test PHPUnit vs Pest
grep -r "class.*Test extends" Modules/*/tests/ | wc -l  # PHPUnit
grep -r "it(" Modules/*/tests/ | wc -l                  # Pest
grep -r "test(" Modules/*/tests/ | wc -l                # Pest
```

### Fase 3: Esecuzione Test
```bash
# Eseguire tutti i test
php artisan test

# Test per modulo specifico
php artisan test Modules/Employee/tests/
php artisan test Modules/Tenant/tests/
```

## Conversione PHPUnit → Pest

### Pattern di Conversione
```php
// PRIMA (PHPUnit)
class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }
}

// DOPO (Pest)
test('example test', function () {
    expect(true)->toBeTrue();
});
```

### Checklist Conversione
- [ ] Identificare tutti i test PHPUnit
- [ ] Convertire sintassi delle classi
- [ ] Aggiornare asserzioni
- [ ] Verificare funzionamento
- [ ] Aggiornare documentazione

## Obiettivi Immediati

### Sprint 1: Analisi e Inventario
1. **Completare inventario** di tutti i test esistenti
2. **Categorizzare** test per tipo e modulo
3. **Identificare** test PHPUnit da convertire
4. **Documentare** stato attuale per ogni modulo

### Sprint 2: Stabilizzazione
1. **Eseguire** tutti i test esistenti
2. **Identificare** test falliti
3. **Riparare** test uno per uno
4. **Convertire** test PHPUnit in Pest

### Sprint 3: Validazione
1. **Verificare** che tutti i test passino
2. **Aggiornare** documentazione
3. **Creare** baseline di test funzionanti
4. **Solo dopo**: procedere con nuovi test

## Comandi Utili

### Analisi Test
```bash
# Conteggio test per modulo
find Modules/ -name "*.php" -path "*/tests/*" | cut -d'/' -f2 | sort | uniq -c

# Ricerca pattern PHPUnit
grep -r "extends.*TestCase" Modules/*/tests/

# Ricerca pattern Pest
grep -r "test\|it\|describe" Modules/*/tests/ --include="*.php"
```

### Esecuzione Test
```bash
# Tutti i test
php artisan test

# Test specifici per modulo
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Test con output dettagliato
php artisan test --verbose
```

### Debug Test
```bash
# Test singolo con debug
php artisan test Modules/Employee/tests/Unit/Models/WorkHourTest.php --verbose

# Test con coverage (se configurato)
php artisan test --coverage
```

## Documentazione Correlata

### File di Riferimento
- [Testing Priority Rule](.cursor/rules/testing-priority-rule.mdc)
- [Testing Priority Memory](.cursor/memories/testing-priority.mdc)
- [PHPUnit Configuration](../phpunit.xml)
- [Pest Configuration](../Modules/*/tests/Pest.php)

### Standard di Qualità
- Tutti i test devono passare prima di creare nuovi test
- Test PHPUnit devono essere convertiti in Pest
- Ogni modulo deve avere la sua suite di test
- Documentazione deve essere mantenuta aggiornata

## Note Tecniche

### Configurazione Pest
- File `Pest.php` in ogni modulo per configurazione specifica
- `TestCase.php` come classe base per test del modulo
- Supporto per test paralleli se configurato
- Integrazione con Laravel Testing Framework

### Best Practices
- Un test file per ogni classe/funzionalità
- Nomi descrittivi per test e asserzioni
- Setup e teardown appropriati
- Isolamento tra test per evitare side effects

---

**Creato**: 2025-01-06
**Stato**: Fase 1 - Analisi in corso
**Prossimo Step**: Completare inventario test esistenti
**Responsabile**: Assistant AI

## Collegamenti

- [Bug Fixes](../bug-fixes/)
- [CMS System](../cms_system.md)
- [Theme Components](../theme_components.md)
- [Modules Documentation](../modules/)

*Ultimo aggiornamento: Gennaio 2025*
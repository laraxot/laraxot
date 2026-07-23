# REGOLA CRITICA: Mai usare RefreshDatabase con database in memoria

## Principio Fondamentale

**MAI usare RefreshDatabase nei test quando phpunit.xml configura `DB_DATABASE=':memory:'`**

## Motivazioni Tecniche

### 1. Isolamento Automatico
- Il database SQLite in memoria (`:memory:`) è già isolato automaticamente per ogni test
- Ogni test parte con un database completamente pulito
- RefreshDatabase diventa ridondante e superfluo

### 2. Conflitti di Migrazione
- RefreshDatabase può causare errori di schema come "duplicate column name"
- Le migrazioni vengono eseguite multiple volte causando conflitti
- Errori di integrità referenziale e vincoli duplicati

### 3. Performance Degradata
- RefreshDatabase rallenta inutilmente l'esecuzione dei test
- Operazioni di rollback/ricreazione database non necessarie
- Overhead di transazioni e cleanup ridondanti

## Regola Generale

```php
// ❌ MAI FARE QUESTO con :memory: database
class MyTest extends TestCase
{
    use RefreshDatabase; // ERRORE!
}

// ❌ MAI FARE QUESTO con :memory: database  
uses(TestCase::class, RefreshDatabase::class); // ERRORE!

// ✅ CORRETTO con :memory: database
class MyTest extends TestCase
{
    // Nessun RefreshDatabase necessario
}

// ✅ CORRETTO con :memory: database
uses(TestCase::class);
```

## Implementazione Immediata

1. Rimuovere `use RefreshDatabase;` da tutti i test
2. Rimuovere `RefreshDatabase::class` da tutti i `uses()`
3. Verificare che phpunit.xml configuri `DB_DATABASE=':memory:'`
4. I test funzioneranno correttamente senza RefreshDatabase

## Controllo Configurazione

```xml
<!-- phpunit.xml - Configurazione corretta per test isolati -->
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

## Casi di Errore Tipici

- `SQLSTATE[HY000]: General error: 1 duplicate column name: created_at`
- Errori di migrazione durante l'esecuzione dei test
- Test che falliscono per problemi di schema invece che logica

## Eccezioni

**NESSUNA ECCEZIONE**: Con `:memory:` database, RefreshDatabase non va mai usato.

## Validazione

Per verificare la conformità:

```bash
# Cerca tutti i test che usano RefreshDatabase (da rimuovere)
grep -r "RefreshDatabase" Modules/*/tests/
grep -r "use.*RefreshDatabase" Modules/*/tests/
```

## Data di Implementazione

2025-01-09 - Regola implementata dopo analisi errori di test

## Priorità

**CRITICA** - Implementazione immediata obbligatoria

---

*Questa regola è permanente e non ammette eccezioni nel contesto Laraxot con database :memory:*

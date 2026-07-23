# Regola Architetturale: No Redundant Static Methods

## 🚨 Problema Identificato

Molti modelli nel progetto contengono **metodi statici ridondanti** che semplicemente proxyano ai metodi della classe base (Eloquent). Questi metodi violano il principio DRY e aggiungono complessità inutile.

## ❌ Esempio SBAGLIATO

```php
class TimeEntry extends BaseModel
{
    // ❌ METODI RIDONDANTI - GIÀ DISPONIBILI DA ELOQUENT
    public static function create(array $attributes = []): static
    {
        return parent::create($attributes);
    }

    public static function find(mixed $id, array $columns = ['*']): ?static
    {
        return parent::find($id, $columns);
    }

    public static function query(): Builder
    {
        return parent::query();
    }

    // ... altri 60+ metodi simili
}
```

## ✅ Esempio CORRETTO

```php
class TimeEntry extends BaseModel
{
    // ✅ NESSUN METODO RIDONDANTE
    // Tutti i metodi query sono già disponibili tramite ereditarietà

    // ✅ SOLO METODI CON LOGICA BUSINESS SPECIFICA
    public function calculateTotalHours(): float
    {
        // Logica business specifica
    }

    public function isApproved(): bool
    {
        // Logica business specifica
    }
}
```

## 📋 Lista Metodi da NON Replicare

### **Metodi Query Base (Già in Eloquent)**
```php
// ❌ NON REPLICARE QUESTI
create()
find()
query()
on()
where()
whereDate()
whereNotNull()
orderBy()
latest()
first()
firstOrFail()
all()
get()
count()
limit()
```

### **Metodi Join (Già in Eloquent)**
```php
// ❌ NON REPLICARE QUESTI
join()
leftJoin()
rightJoin()
groupBy()
having()
distinct()
```

### **Metodi Where (Già in Eloquent)**
```php
// ❌ NON REPLICARE QUESTI
whereRaw()
orWhereRaw()
whereIn()
whereNotIn()
whereBetween()
orWhereBetween()
whereNotBetween()
orWhereNotBetween()
whereNull()
orWhereNull()
whereDate() (duplicate)
orWhereDate()
whereTime()
orWhereTime()
whereDay()
whereMonth()
whereYear()
```

### **Metodi Exist (Già in Eloquent)**
```php
// ❌ NON REPLICARE QUESTI
exists()
doesntExist()
```

## 🎯 Quando Aggiungere Metodi Statici

### **✅ CASI VALIDI**

1. **Scopes Personalizzati**
```php
public function scopePending(Builder $query): Builder
{
    return $query->where('status', 'pending');
}
```

2. **Factory Methods con Logica**
```php
public static function createWithDefaults(array $attributes = []): static
{
    return static::create(array_merge([
        'status' => 'pending',
        'created_by' => auth()->id(),
    ], $attributes));
}
```

3. **Finder Methods con Logica**
```php
public static function findActive(int $id): ?static
{
    return static::where('id', $id)
        ->where('is_active', true)
        ->first();
}
```

## 🔍 Come Identificare Metodi Ridondanti

### **Pattern da Cercare**
```php
// ❌ PATTERN RIDONDANTE
public static function nomeMetodo(...$args): Tipo
{
    return parent::nomeMetodo(...$args);
}

// La firma è IDENTICA al parent
// Il corpo è SOLO una chiamata parent
```

### **Verifica Automatica**
```bash
# Cerca metodi che chiamano solo parent::
grep -r "public static function.*parent::" Modules/
```

## 🛠️ Processo di Refactoring

### **Step 1: Analisi**
```bash
# Conta metodi ridondanti
grep -c "public static function" Modules/Employee/app/Models/TimeEntry.php
```

### **Step 2: Rimozione**
1. Identifica tutti i metodi che chiamano solo `parent::`
2. Rimuovili completamente
3. Mantieni solo metodi con logica business

### **Step 3: Verifica**
```bash
# PHPStan
./vendor/bin/phpstan analyse --level=10

# PHPMD
./vendor/bin/phpmd text cleancode,codesize,design

# PHPInsights
./vendor/bin/phpinsights analyse --format=json
```

## 📊 Benefici

### **1. Riduzione Complessità**
- **Prima**: 500+ linee
- **Dopo**: 100-150 linee
- **Riduzione**: 70-80%

### **2. Migliore Performance**
- Meno codice da caricare
- Compilazione più veloce
- Autoloading efficiente

### **3. Manutenibilità**
- Struttura più chiara
- Meno codice da testare
- Più facile da capire

### **4. PHPStan Compliance**
- Meno falsi positivi
- Type analysis più accurata
- Errori più significativi

## 🧪 Test di Conformità

### **Test 1: Verifica Ridondanza**
```php
// Dopo il refactoring, questo dovrebbe funzionare
TimeEntry::where('status', 'pending')->get();
// ✅ Funziona perché ereditato da Eloquent
```

### **Test 2: Business Logic**
```php
$entry = TimeEntry::find(1);
$hours = $entry->calculateTotalHours();
// ✅ Logica business preservata
```

### **Test 3: Scopes**
```php
TimeEntry::pending()->get();
// ✅ Scope personalizzato funziona
```

## 📚 Esempi dal Progetto

### **TimeEntry (Refactored)**
- **Prima**: 537 linee, 67+ metodi ridondanti
- **Dopo**: ~120 linee, solo business logic
- **Riduzione**: 77%

### **Altri Modelli da Controllare**
```bash
# Modelli potenzialmente affetti
Modules/*/app/Models/*.php
```

## 🔄 Workflow di Sviluppo

### **Quando Creare un Nuovo Modello**
1. Estendi `BaseModel` (o classe base appropriata)
2. **NON** aggiungere metodi query standard
3. Aggiungi solo:
   - Relazioni (`belongsTo`, `hasMany`, etc.)
   - Scopes personalizzati
   - Metodi business logic
   - Casts, fillable, etc.

### **Quando Refactorare un Modello Esistente**
1. Analizza metodi statici
2. Rimuovi quelli ridondanti
3. Esegui test di regressione
4. Verifica qualità codice
5. Documenta i cambiamenti

## 💡 Filosofia

### **Principio DRY**
"Ogni pezzo di conoscenza deve avere una singola, non ambigua, rappresentazione autorevole all'interno di un sistema."

### **Principio KISS**
"La semplicità dovrebbe essere un obiettivo chiave nel design, e la complessità non necessaria dovrebbe essere evitata."

### **Trust the Framework**
Eloquent/Laravel fornisce già un'API completa e testata. Non reinventare la ruota.

## 📈 Metriche di Successo

### **Quantitative**
- Riduzione linee codice: ≥ 50%
- PHPStan errors: 0
- PHPMD warnings: 0
- PHPInsights score: ≥ 95%

### **Qualitative**
- Struttura più chiara
- Business logic evidente
- Facile da estendere
- Documentazione aggiornata

## 🚀 Template per Nuovi Modelli

```php
<?php

declare(strict_types=1);

namespace Modules\Example\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        // Campi specifici
    ];

    protected function casts(): array
    {
        return [
            // Casts specifici
        ];
    }

    // ✅ RELAZIONI
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ SCOPES PERSONALIZZATI
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ✅ BUSINESS LOGIC
    public function calculateSomething(): float
    {
        // Logica specifica
    }

    // ❌ NESSUN METODO QUERY STANDARD
    // create(), find(), where(), etc. sono già in Eloquent
}
```

## 🔗 Collegamenti Utili

- [Eloquent Documentation](https://laravel.com/docs/eloquent)
- [PHPStan Level 10 Rules](../../Xot/docs/phpstan/level10-rules.md)
- [DRY Principle](../../Xot/docs/architectural_rules/dry-principle.md)
- [KISS Principle](../../Xot/docs/architectural_rules/kiss-principle.md)

---

**Regola Fondamentale**: Se un metodo è identico a quello della classe base, non replicarlo. Usa l'ereditarietà.
# PHPStan Analysis Report

## Data Analisi
12 Dicembre 2025

## Moduli Analizzati

### ✅ Modulo Activity - Parzialmente Risolto

**Errori Corretti:**
- Type hints in `ActivityLogger.php`
- Metodi statici mancanti nel modello `Activity`
- Gestione tipi in `ListLogActivities.php`

**Problemi Rimanenti:**
- Riferimento circolare in `ActivityLogger.php` (builder chain eccessivo)
- Metodi Builder non riconosciuti da PHPStan

### ✅ Modulo Cms - Parzialmente Risolto

**Errori Corretti:**
- Metodo `getFinder()` non esistente in `BlockData.php`
- Sintassi parentesi graffe

**Problemi Rimanenti:**
- Cast `array|string|null` to string in blocchi Filament
- PHPStan non riconosce i tipi restituiti da `__()`

## Pattern di Errori Comuni

### 1. Metodi Statici Mancanti
```php
// Errore: Call to an undefined static method
Activity::create()
Activity::where()

// Soluzione: Aggiungere metodi statici nel modello
public static function create(array $attributes = []): static
{
    return parent::create($attributes);
}
```

### 2. Type Hint da Translation Helper
```php
// Errore: Cannot cast array|string|null to string
return __('module::key');

// Soluzione: Cast diretto con gestione
return (string) __('module::key');
```

### 3. Metodi Builder Non Riconosciuti
```php
// Errore: Call to undefined method Builder::count()
$query->count()

// Soluzione: Aggiungere metodi statici nel modello
public static function count(): int
{
    return static::query()->count();
}
```

## Strategie di Correzione Applicate

### 1. Aggiunta Metodi Statici
Per risolvere i problemi con classi che estendono classi esterne (SpatieActivity), ho aggiunto i metodi statici necessari direttamente nel modello.

### 2. Type Hints Robusti
Ho utilizzato type hints specifici e gestione dei casi limite per i metodi che possono restituire tipi diversi.

### 3. Gestione Eccezioni
Ho aggiunto try-catch dove necessario per gestire metodi che potrebbero non esistere.

## Problemi Aperti

### 1. PHPStan e Classi Esterne
PHPStan ha difficoltà con le classi che estendono classi di pacchetti esterni (Spatie, Filament). Le soluzioni temporanee includono:
- Aggiunta di metodi statici wrapper
- Utilizzo di stub files
- Baseline PHPStan per errori noti

### 2. Translation Helper Types
Il helper `__()` di Laravel può restituire diversi tipi (string, array, Htmlable). PHPStan Level 10 è molto rigoroso su questi aspetti.

### 3. Builder Chain Eccessivo
In `ActivityLogger.php`, il builder chain diventa troppo complesso per PHPStan, causando riferimenti circolari.

## Raccomandazioni

### 1. Creare Stub Files PHPStan
```php
// phpstan-stubs/ActivityStub.php
<?php

namespace Modules\Activity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static static create(array $attributes)
 * @method static Builder|static where($column, $operator = null, $value = null)
 * @method static Builder|static query()
 */
abstract class Activity extends Model
{
    // Stub methods for PHPStan
}
```

### 2. Baseline PHPStan
Aggiungere errori noti al baseline per ignorare temporaneamente problemi non critici:

```neon
# phpstan.neon
parameters:
    ignoreErrors:
        - '#Cannot cast array\|string\|null to string#'
        - '#Call to an undefined method.*Builder.*#'
```

### 3. Type Assertions Specifiche
Utilizzare assert più specifici dove possibile:

```php
/** @var string $label */
$label = __('module::key');
return $label;
```

## Politica Laraxot Applicata

1. **Logic**: Type safety rigoroso per prevenire errori runtime
2. **Philosophy**: DRY - evitare duplicazione delle correzioni
3. **Politics**: Standardizzare approccio PHPStan in tutti i moduli
4. **Religion**: Strong typing attraverso PHPDoc e type hints
5. **Zen**: Accettare limitazioni degli strumenti esterni e trovare soluzioni eleganti

## Prossimi Passi

1. Creare stub file per SpatieActivity
2. Implementare baseline PHPStan per errori noti
3. Continuare correzione sistematica degli altri moduli
4. Testare le correzioni per assicurare che non introducano regressioni

## Metriche

- **Moduli Analizzati**: 2 (Activity, Cms)
- **File Corretti**: 6
- **Errori Risolti**: ~15
- **Errori Rimanenti**: ~8
- **Copertura**: Parziale

La correzione continuerà fino a raggiungere zero errori PHPStan in tutti i moduli.
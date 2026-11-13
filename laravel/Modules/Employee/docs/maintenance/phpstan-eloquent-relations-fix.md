# PHPStan Eloquent Relations Fix - Employee Module

## Overview

Questo documento descrive la risoluzione degli errori PHPStan relativi ai tipi generici nelle relazioni Eloquent del modulo Employee, specificamente per i modelli `Attendance` e `Timbratura`.

## Errore Identificato

**Tipo:** Template Type Covariance Error  
**Messaggio:** `Template type TDeclaringModel on class BelongsTo is not covariant`  
**File Coinvolti:**
- `Employee/app/Models/Attendance.php` (linee 78, 88, 98)
- `Employee/app/Models/Timbratura.php` (linee 78, 88, 98)

## Causa Tecnica

PHPStan rileva un problema di covarianza nei tipi generici delle relazioni Eloquent. Il problema si verifica quando si utilizza `$this` come tipo generico nel PHPDoc delle relazioni `BelongsTo`, poiché `$this` non è covariante con il tipo specifico del modello.

### Codice Problematico

```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## Soluzione Implementata

### Pattern Corretto per Relazioni BelongsTo

```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### Regole di Tipizzazione per Relazioni Eloquent

1. **FQCN Obbligatorio**: Utilizzare sempre il nome completo della classe invece di `$this`
2. **Namespace Completi**: Specificare il namespace completo per entrambi i tipi generici
3. **Coerenza**: Mantenere coerenza tra il tipo di ritorno del metodo e il PHPDoc

### Template di Correzione

Per tutte le relazioni `BelongsTo` nel modulo Employee:

```php
/**
 * Get the [description] that owns the [model].
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\[ModelName]>
 */
public function [relationName](): BelongsTo
{
    return $this->belongsTo(User::class, '[foreign_key]');
}
```

## Implementazione Specifica

### Modello Attendance

```php
/**
 * Get the user that owns the attendance record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/**
 * Get the user that created the attendance record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function createdBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'created_by');
}

/**
 * Get the user that updated the attendance record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function updatedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'updated_by');
}
```

### Modello Timbratura

```php
/**
 * Get the user that owns the timbratura record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Timbratura>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/**
 * Get the user that created the timbratura record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Timbratura>
 */
public function createdBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'created_by');
}

/**
 * Get the user that updated the timbratura record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Timbratura>
 */
public function updatedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'updated_by');
}
```

## Best Practices per Relazioni Eloquent

1. **Tipizzazione Completa**: Sempre specificare entrambi i tipi generici
2. **Documentazione Chiara**: Includere descrizioni significative nei PHPDoc
3. **Namespace Espliciti**: Non fare affidamento su import o alias
4. **Validazione PHPStan**: Testare sempre con PHPStan livello 9+

## Validazione

Dopo l'implementazione, verificare con:

```bash
cd /var/www/html/_bases/base_techplanner_fila3_mono/laravel
./vendor/bin/phpstan analyze Modules/Employee --level=9
```

## Collegamenti

- [Root PHPStan Error Analysis Guide](../../docs/phpstan-error-analysis-guide.md)
- [Employee Module XotBase Extension Rules](./xotbase_extension_rules.md)
- [Employee Module Technical Implementation](./technical_implementation.md)

*Ultimo aggiornamento: Luglio 2025*

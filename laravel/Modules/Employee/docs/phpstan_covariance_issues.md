# PHPStan Covariance Issues - Eloquent Relationships

## Problema

PHPStan livello 9+ genera errori di covariance sui tipi generici delle relazioni Eloquent `BelongsTo`:

```
Method Modules\Employee\Models\Attendance::user() should return 
Illuminate\Database\Eloquent\Relations\BelongsTo<Modules\User\Models\User, Modules\Employee\Models\Attendance> 
but returns Illuminate\Database\Eloquent\Relations\BelongsTo<Modules\User\Models\User, $this(Modules\Employee\Models\Attendance)>.

Template type TDeclaringModel on class Illuminate\Database\Eloquent\Relations\BelongsTo is not covariant.
```

## Causa Root

Laravel/Eloquent utilizza template generics non covarianti per le relazioni. PHPStan livello 9+ è estremamente rigoroso su questo aspetto e richiede che i tipi generici siano esattamente compatibili.

Il problema si verifica quando si dichiara:
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## Soluzione Standard

Utilizzare `$this` invece del nome completo della classe nel secondo parametro generico:

```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## Pattern di Applicazione

### ✅ CORRETTO
```php
/**
 * Get the user that owns the record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/**
 * Get the user that created the record.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
 */
public function createdBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'created_by');
}
```

### ❌ ERRATO
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## Altri Tipi di Relazioni

### HasMany
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\HasMany<\Modules\Employee\Models\Timbratura>
 */
public function timbrature(): HasMany
{
    return $this->hasMany(Timbratura::class);
}
```

### BelongsToMany
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\Modules\User\Models\Role>
 */
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class);
}
```

### HasOne
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\HasOne<\Modules\Employee\Models\Profile>
 */
public function profile(): HasOne
{
    return $this->hasOne(Profile::class);
}
```

## Script di Correzione Automatica

Per applicare questa correzione su tutti i modelli:

```bash
#!/bin/bash
# Fix PHPStan covariance issues in BelongsTo relationships

find Modules -name "*.php" -type f -exec sed -i 's/@return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<\\([^,]*\\), \\([^>]*\\)>/@return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<\\1, $this>/g' {} \;
```

## Validazione

Dopo aver applicato le correzioni, verificare con:

```bash
./vendor/bin/phpstan analyze Modules/Employee --level=9 --no-progress
```

## Note Tecniche

1. **Covariance**: Il template type `TDeclaringModel` non è covariante in Laravel/Eloquent
2. **$this**: Rappresenta l'istanza corrente del modello, risolvendo il problema di covariance
3. **Compatibilità**: Questa soluzione è compatibile con tutte le versioni di Laravel 8+
4. **Performance**: Non ha impatto sulle performance, è solo una questione di tipizzazione statica

## Riferimenti

- [PHPStan Covariance Documentation](https://phpstan.org/blog/whats-up-with-template-covariant)
- [Laravel Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Modules/Employee/docs/README.md](./README.md)

## Backlink

- [Root PHPStan Rules](../../../docs/phpstan_rules.md)
- [Employee Module Structure](./module_structure.md)

*Ultimo aggiornamento: 2025-07-31*
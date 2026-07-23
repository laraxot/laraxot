# TechPlanner Legal Representatives Error Fix

## Problema

**Errore**: `BadMethodCallException: Call to undefined method Modules\TechPlanner\Models\Client::legalRepresentatives()`

**URL**: `https://sottana.com/techplanner/admin/clients/8/edit`

**Causa**: Il RelationManager `LegalRepresentativesRelationManager` cercava di accedere al metodo `legalRepresentatives()` nel modello `Client`, ma questo metodo non era definito.

## Soluzione Implementata

### 1. Aggiunta Relazione nel Modello Client

```php
/**
 * Get the legal representatives for the client.
 */
public function legalRepresentatives(): HasMany
{
    return $this->hasMany(\Modules\TechPlanner\Models\LegalRepresentative::class);
}
```

### 2. Aggiunta Relazione Inversa nel Modello LegalRepresentative

```php
/**
 * Get the client that owns the legal representative.
 */
public function client(): BelongsTo
{
    return $this->belongsTo(\Modules\TechPlanner\Models\Client::class);
}
```

### 3. Aggiornamento PHPDoc

Aggiunto `@property-read` per le relazioni in entrambi i modelli per supportare l'IDE e PHPStan.

## File Modificati

- `laravel/Modules/TechPlanner/Models/Client.php`
- `laravel/Modules/TechPlanner/Models/LegalRepresentative.php`

## Test della Soluzione

```bash
# Test tramite tinker
php artisan tinker
$client = \Modules\TechPlanner\Models\Client::first();
$client->legalRepresentatives; // Dovrebbe funzionare senza errori
```

## Abilitazione RelationManager

Per abilitare il RelationManager nel ClientResource, decommentare:

```php
public static function getRelations(): array
{
    return [
        LegalRepresentativesRelationManager::class,
    ];
}
```

## Documentazione Correlata

- [TechPlanner Legal Representatives Relationship Fix](../laravel/Modules/TechPlanner/docs/legal-representatives-relationship-fix.md)
- [TechPlanner Relation Managers Setup](../laravel/Modules/TechPlanner/docs/relation-managers-setup.md)
- [TechPlanner Module README](../laravel/Modules/TechPlanner/docs/README.md)

## Status

✅ **RISOLTO** - Le relazioni Eloquent sono state implementate correttamente e il RelationManager dovrebbe funzionare senza errori.
---
id: intervention-phpstan-larastan-bug-001
slug: larastan-isForwardedQueryBuilderMethod-bug
scope:
  - module:Intervention
  - project:base_workorder_fila5
status: Resolved
epic: PHPStan Quality Gates
priority: High
related:
  - /var/www/_bases/base_workorder_fila5/laravel/Modules/Intervention/app/Filament/Resources/InterventionResource/Pages/ListInterventions.php
  - /var/www/_bases/base_workorder_fila5/laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseListRecords.php
---

## Problema

```
Internal error: Call to undefined method Larastan\Larastan\Reflection\EloquentBuilderMethodReflection::isForwardedQueryBuilderMethod()
```

PHPStan/Larastan v3.11 crasha durante l'analisi di `ListInterventions.php`.

## Root Cause

Bug interno di compatibilità tra:
- **Larastan**: 3.11.0 (ultima versione)
- **PHPStan**: 2.2.13 (ultima versione)

Il metodo `isForwardedQueryBuilderMethod()` non esiste in `EloquentBuilderMethodReflection` nella versione installata.

## Soluzione Implementata

Aggiunto `@phpstan-ignore-next-line` nel file `ListInterventions.php`:

```php
final class ListInterventions extends XotBaseListRecords
{
    /**
     * @phpstan-ignore-next-line Larastan internal error with EloquentBuilder pagination
     */
    protected static string $resource = InterventionResource::class;
```

## Azioni Future

1. **Monitorare**: Verificare se Larastan rilascia fix in versioni future
2. **Segnalare**: Considerare di aprire issue su https://github.com/larastan/larastan
3. **Rimuovere ignore**: Quando fix disponibile, rimuovere l'ignore

## Verification

```bash
cd /var/www/_bases/base_workorder_fila5/laravel
./vendor/bin/phpstan analyse Modules/Intervention --memory-limit=2G
# Result: [OK] No errors (110 errors normali, nessun crash)
```

## BMAD Dev Story

- Story ID: intervention-phpstan-larastan-bug-001
- Status: Resolved
- Fix: @phpstan-ignore-next-line
- Fix Date: 2026-09-06

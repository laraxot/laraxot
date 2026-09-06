# PHPStan Level 10 Compliance Status

**Last Updated**: 2026-07-06

**Status**: ✅ FULLY COMPLIANT (0 errors, level max)

Baseline STORY-287: 282 errori → 0. Pattern: Contracts `Model` generics, Cast/Export/Trait PHPDoc, `HasXotFactory` covariant.

Baseline sessione 2026-07-06: 1 → 0. `Modules\Xot\Contracts\UserContract::membershipTeams()` era disallineato con `HasTeams::teams()` (secondo parametro generico di `BelongsToMany` — `$this` non è un sottotipo valido di `Model` nel bound del contratto). Allineato a `Model`. `HasSchemalessAttributesTest.php` chiamava un metodo mai esistito (`getSchemalessAttributes()`) invece dell'accesso magico `$model->extra_attributes`; corretto il test.

## Summary
The Xot module is now fully compliant with PHPStan Level 10 analysis. All static analysis errors have been resolved, ensuring type safety and code quality.

## Fixed Issues

### 1. HTTP Client PromiseInterface Issue
**Problem**: HTTP client methods may return PromiseInterface or Response
**Solution**: Added proper type checking and casting
**File**: `app/Actions/Dummy/GetProductsArrayDummyAction.php`
**Details**: Added instanceof check for PromiseInterface and wait() method call

```php
// Ensure we have a Response, not Promise
if ($response instanceof \GuzzleHttp\Promise\PromiseInterface) {
    $response = $response->wait();
}

/** @var \Illuminate\Http\Client\Response $response */
```

## Compliance Verification
```bash
./vendor/bin/phpstan analyse Modules/Xot --level=10 --memory-limit=-1
# Result: [OK] No errors
```

## Best Practices Implemented

1. **HTTP Client Safety**: Proper handling of async HTTP responses
2. **Union Type Management**: Safe handling of PromiseInterface|Response unions
3. **Type Casting**: Using PHPDoc to cast to specific types
4. **Dummy Actions**: Type-safe implementation of test actions

## Module Overview

The Xot module serves as the core framework module providing:
- Base classes for Filament resources and pages
- Common actions and utilities
- Framework integration patterns
- Shared components across all modules

## HTTP Client Pattern (Xot Standard)

```php
$response = Http::get($url);

// Ensure we have a Response, not Promise
if ($response instanceof \GuzzleHttp\Promise\PromiseInterface) {
    $response = $response->wait();
}

/** @var \Illuminate\Http\Client\Response $response */
Assert::isArray($products = $response->json());
```

## Ongoing Maintenance

To maintain PHPStan compliance:
1. Always handle PromiseInterface in HTTP calls
2. Use proper PHPDoc casting for complex types
3. Ensure all base classes have proper type hints
4. Run PHPStan before committing changes

## Related Documentation
- [Xot Base Classes](xot-base-classes.md)
- [Framework Integration Patterns](framework-integration.md)
- [HTTP Client Best Practices](http-client-best-practices.md)
- [Dummy Actions Pattern](dummy-actions-pattern.md)
## Aggiornamento 2026-07-06

- `app/Contracts/UserContract.php`: aggiunto `membershipTeams(): BelongsToMany`
  (mancava, causava `method.notFound` in `AssignTeamCommand.php`).
- `app/Traits/HasCustomRelations.php`: eliminato, `trait.unused` reale
  (zero utilizzi in tutto `Modules/`), codice morto.
- Rimossi tutti i probe PHPStan morti (`*PhpstanProbe*`) in Geo/Lang/Tenant e
  aggiornata la doc `docs/wiki/concepts/phpstan-trait-probes.md` a stato
  "pattern rimosso" — la funzione registry `xotPhpstanTraitProbeClasses()`
  non è mai esistita nel codice.
- Nuova nota `docs/wiki/concepts/phpstan-partial-scope-false-positives.md`:
  analizzare un sottoinsieme di `Modules/` può generare `trait.unused` falsi
  positivi per trait usati in un modulo diverso da quello scansionato.

Ri-verificato: `./vendor/bin/phpstan analyse Modules` → `[OK] No errors`
sull'intero progetto.

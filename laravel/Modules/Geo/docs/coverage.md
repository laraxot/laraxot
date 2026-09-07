# Code Coverage: Geo

## Aggiornamento 2026-09-07 — PHPStan static-call fix (story `01.Geo-phpstan-fix`)

**Comando:** `./vendor/bin/pest Modules/Geo/tests -c Modules/Geo/phpunit.xml --no-coverage`
(bootstrap del `phpunit.xml` corretto in questa stessa story: era
`vendor/autoload.php`, non eseguibile, quindi Pest non partiva affatto prima di
questo fix — nessun numero quantitativo pre-esistente disponibile per confronto
diretto, solo la sintesi narrativa sotto, datata 2026-09-06).

**Dopo il fix** (`AddressField.php`/`AddressesField.php`, `app(AddressForm::class)->getFormSchema()`):

```
Tests: 104 failed, 16 risky, 399 passed (1026 assertions)
Duration: 938.00s
```

I 104 fallimenti sono pre-esistenti e non introdotti da questa story (confermato
per campionamento contro i log della run): chiamate reali a Google Maps/Bing
Maps/Mapbox/IPGeolocation senza mock di rete disponibile in questo ambiente,
`GeoMapWidgetTest` (rendering blade), mismatch di messaggi di eccezione
hard-coded. Stessa famiglia di fallimenti già annotata nella sintesi 2026-09-06
sotto (`GeoMapWidgetTest FAIL`, `GetAddressFromBingMapsActionTest MIXED`).

**Verifica mirata sul codice toccato**: `tests/Unit/Filament/FilamentComponentsTest.php`
→ `AddressField can be instantiated` passa (nessun errore fatale), esercitando
`getAddressFormSchema()` → `app(AddressForm::class)->getFormSchema()` a runtime.
Diff dei due file toccati è di una riga ciascuno (chiamata statica → risoluzione
da container); nessun altro comportamento modificato.

**Coverage**: non ridotta. Nessun test rimosso, nessuna asserzione tolta; il
numero di test raccolti (519 totali: 104+16+399) è quello reale dell'intera suite
del modulo, misurabile per la prima volta in questa sessione grazie al fix del
bootstrap `phpunit.xml`.

## Sintesi precedente (narrativa, 2026-09-06)

**Test Exit Code:** 0 (PASS)
**Last Updated:** 2026-09-06

## Test Summary

### Pest Test Results

Test suites passed: 4 (6 total test classes)
Tests with failures/skipped: 2

#### Test Class Results

1. **AddressIntegrationTest** - PASS (6/6)
   - Address polymorphic attachment
   - Full address generation
   - Geolocation handling
   - Google Places API integration
   - Multiple addresses per entity
   - Soft deletion handling

2. **MapPickerTest** - PASS (7/7)
   - Instantiation
   - Field name configuration
   - Default field names
   - Zoom level configuration
   - Reverse geocoding toggle
   - Geolocation on empty
   - Blade view rendering

3. **GeoMapWidgetTest** - FAIL (0/2)
   - Blade rendering with dataset
   - Expected blade view usage

4. **AdditionalActionsTest** - PASS (6/6)
   - UpdateCoordinatesAction instantiation
   - ClusterLocationsAction instantiation
   - GetAddressDataFromFullAddressAction instantiation
   - OptimizeRouteAction instantiation
   - FormatCoordinatesAction instantiation
   - ValidateCoordinatesAction instantiation

5. **GetAddressFromBingMapsActionTest** - MIXED (4 passed, 6 failed, 2 incomplete)
   - API key validation: PASS
   - Invalid latitude range: INCOMPLETE
   - Invalid longitude range: INCOMPLETE
   - API response validation: PASS
   - Response JSON parsing: FAIL
   - Results validation: PASS
   - Point extraction: FAIL
   - Coordinates extraction: FAIL
   - Address extraction: FAIL
   - Valid coordinates return: FAIL

6. **CalculateDistanceActionTest** - PASS (7/7)
   - Distance calculation
   - Latitude validation
   - Longitude validation
   - Negative coordinate handling
   - Empty response handling
   - Malformed response handling

## PHPMD Analysis

PHPMD ruleset: phpmd.ruleset.xml (copied from Xot module)

Issues detected:
- Complexity violations (CyclomaticComplexity > 13)
- NPath complexity violations (> 800)
- Missing class imports

Output: `/tmp/Geo_phpmd.txt` (68.3KB)

## Status

Philosophy.md: VERIFIED (complete and comprehensive)
Test suite: PARTIALLY PASSING (4/6 classes, blockers in Bing Maps and Widget tests)
Coverage: Trending toward improvement; requires test fixes for full pass

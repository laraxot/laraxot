---
title: "Audit @phpstan-ignore in Xot"
type: report
created_at: '2026-09-01'
qmd: "audit phpstan ignore soppressioni xot has-xot-table updater enumtrait stale verificato rimosso"
---

# Audit `@phpstan-ignore` — Xot

12 occorrenze all'inizio dell'audit. Esito, verificato empiricamente (rimozione +
`phpstan analyse Modules/Xot`, non a naso):

## Legittime, lasciate — 4

- `HasUuid.php`, `HasDynamicFillable.php`, `TypedHasRecursiveRelationships.php`
  (`trait.unused`): trait pubblici di piattaforma, zero consumer in questo repo,
  motivazione già scritta inline ("si cancella solo se il pattern viene ritirato").
- `EnumIntegerTrait.php` (`trait.unused`): stesso pattern, motivazione già presente.

## Stale, rimosse — 8

Tutte verificate rimuovendo il commento e rilanciando `phpstan analyse Modules/Xot`:
zero errori in tutti i casi, quindi la soppressione non copriva più nulla.

- `Traits/Updater.php` — 2× `return.type` su `creator()`/`updater()`. Il metodo
  gemello `deleter()`, stesso identico pattern (`XotData::make()->getProfileClass()`
  → `belongsTo()`), non aveva mai avuto la soppressione: prova diretta che non
  serviva nemmeno agli altri due.
- `Traits/EnumTrait.php:124` — `callable.nonCallable` su `$definition($table)`
  dentro `columns()`. `getColumnDefinitions()` dichiara già
  `@return array<string, callable(Blueprint): void>`: il tipo era corretto, la
  soppressione ridondante.
- `Filament/Traits/HasXotTable.php` — 5 occorrenze: 1× `argument.type` su
  `->filters($this->getXotTableFilters())`, 4× `function.alreadyNarrowedType,
  method.notFound` sui `method_exists($resource, 'canView'|'canEdit'|'canDelete')`
  e `method_exists($this, 'getRelationship')` in `getXotTableActions()`. Ipotesi
  iniziale (narrowing bloccato dal tipo statico di `$resource`) non confermata:
  zero errori senza le soppressioni.

Nota: se questi errori dovessero ricomparire in futuro (upgrade PHPStan/Larastan,
o narrowing più stretto su `$resource`), il pattern corretto per i `method_exists`
duck-typed resta la soppressione — non va reintrodotta "a scatola chiusa" ma
riverificata con lo stesso metodo (rimuovi, rilancia, guarda cosa succede davvero).

---
title: "Tipi dei parametri nelle closure Filament"
module: Xot
type: concept
status: approved
language: it-IT
created: 2026-08-19
updated: 2026-08-19
qmd: "filament closure parametri tipi record livewire records state set get type coverage phpstan"
related:
  - ../../stories/5.20.type-coverage-campaign.story.md
  - ../../../../../../docs/chat/type-coverage-campaign.md
---

# Tipi dei parametri nelle closure Filament

`tomasvotruba/type-coverage` (arriva con `pestphp/pest-plugin-type-coverage`, registrato in
automatico da `phpstan/extension-installer`) chiede il 99 % di parametri tipizzati. Nel
codice Filament la stragrande maggioranza dei parametri senza tipo sta nelle closure di
`->action()`, `->default()`, `->options()`, `->visible()`, `->afterStateUpdated()`.

Filament **inietta per nome**: `resolveDefaultClosureDependencyForEvaluationByName()` in
`Filament\Actions\Action` mappa il nome del parametro al valore. Il tipo dichiarato non
cambia l'iniezione — deve solo accettare il valore che arriva a runtime.

## Tabella dei tipi

| Parametro | Cosa inietta Filament | Tipo da dichiarare |
|-----------|----------------------|--------------------|
| `$record` | `$this->getRecord()` | `Model` (o il model concreto se la classe è legata a uno solo); `?Model` sulle header action, dove il record può mancare |
| `$livewire` | `$this->getLivewire()` | la classe concreta della pagina: `ListRecords`, `RelationManager`, `Page`. Se serve solo `dispatch()`, `Livewire\Component`. Se serve la tabella, `Filament\Tables\Contracts\HasTable` |
| `$records`, `$selectedRecords` | `getIndividuallyAuthorizedSelectedRecords()` | `Illuminate\Support\Collection\|Illuminate\Support\LazyCollection` — **non** `Eloquent\Collection` da sola: la firma di Filament è `EloquentCollection\|Collection\|LazyCollection` |
| `$data` | `$this->getData()` | `array` + `@param array<string, mixed>` |
| `$state` | stato del componente | il tipo del campo: `?string` per `TextInput`, `int\|string\|null` per una `Select` con chiavi numeriche (PHP converte `'2023' => …` in chiave `int`) |
| `$set`, `$get` | utility di schema | `callable` (già così nel codice esistente) |
| `$action` | l'action stessa | quasi sempre **inutilizzato**: cancellare il parametro invece di tipizzarlo |
| `$table` | `$this->getTable()` | `Filament\Tables\Table` |

## Regola pratica: prima togliere, poi tipizzare

Un parametro dichiarato e mai usato non va tipizzato, va **rimosso**. L'iniezione per nome
significa che una closure può dichiarare solo i parametri che le servono, in qualunque
ordine. Nei moduli Ptv/Sigma/Pdnd la firma ricorrente era

```php
->action(function ($livewire, $record, $action): void {
    if (! ($livewire instanceof ListRecords)) {
        return;
    }
    // $record e $action mai usati
```

che diventa

```php
->action(function (ListRecords $livewire): void {
```

Tre parametri senza tipo e una guardia in meno, stesso comportamento.

## Il tipo rende ridondanti le guardie difensive

Quando il parametro era `mixed` il codice si difendeva a mano. Dopo il tipo, quelle guardie
diventano `function.alreadyNarrowedType` e **vanno tolte**, non silenziate:

| Guardia | Dopo il tipo |
|---------|--------------|
| `is_object($record)` | ridondante con `Model $record` |
| `is_object($livewire) && method_exists($livewire, 'getOwnerRecord')` | ridondante con `RelationManager $livewire` |
| `$ownerRecord === null` | ridondante: `RelationManager::getOwnerRecord(): Model` non è nullable |
| `method_exists($record, 'gg')` | **resta**: `Model` non dichiara `gg()`, e PHPStan usa `method_exists()` per restringere |

## `$records` richiede `accessSelectedRecords()`

Da Filament v4, una closure che dichiara `$records` fa passare
`InteractsWithSelectedRecords::getSelectedRecords()`, che lancia

```
LogicException: The action [x] is attempting to access the selected records from the table,
but it is not using [accessSelectedRecords()], so they are not available.
```

Il tipo non c'entra: senza `->accessSelectedRecords()` nella catena, la bulk action esplode
comunque a runtime. Le tre bulk action di `Modules/Ptv` erano in questo stato — tipizzarle
ha fatto emergere il difetto.

## Le closure sopra una catena fluente

Un docblock `@param` messo prima di `->action(...)` **non** viene letto: PHPStan lo associa
alla chiamata di metodo, non alla closure. Va messo dentro la chiamata, immediatamente prima
della closure:

```php
->action(
    /** @param Collection<int, Model>|LazyCollection<int, Model> $records */
    function (Collection|LazyCollection $records): void {
        // ...
    }
);
```

## Nota sulla verifica

`typeCoverage.*` **non si verifica su un sottopercorso**: `ParamTypeCoverageRule` apre con

```php
if (! ScopeConfigurationResolver::areFullPathsAnalysed($scope)) {
    return [];
}
```

Analizzare `Modules/Ptv` o un singolo file restituisce sempre `[OK] No errors` anche con
decine di parametri senza tipo. L'unica misura valida è il run sui `paths` dichiarati in
`phpstan.neon`, cioè `./vendor/bin/phpstan analyse Modules`. Il run per-file resta utile per
gli **altri** identifier (`argument.type`, `alreadyNarrowedType`) che il tipo appena aggiunto
può far emergere.

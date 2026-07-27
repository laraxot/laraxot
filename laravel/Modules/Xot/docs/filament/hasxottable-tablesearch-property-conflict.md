---
title: "HasXotTable — conflitto proprietà $tableSearch (fatal bootstrap-wide)"
type: bugfix
module: Xot
tags: [filament, phpstan, trait, tableSearch, fatal-error]
created: 2026-07-27
updated: 2026-07-27
---

# `$tableSearch`: conflitto di composizione trait → fatal su quasi tutti i moduli

## Sintomo

```
PHP Fatal error: Filament\Resources\Pages\ListRecords and
Modules\Xot\Filament\Traits\HasXotTable define the same property
($tableSearch) in the composition of
Modules\Xot\Filament\Resources\Pages\XotBaseListRecords. However, the
definition differs and is considered incompatible.
```

Questo fatal si verifica al **bootstrap/autoload** (non a runtime di una richiesta
specifica), quindi qualunque comando che carichi la classe — PHPStan, `php artisan`,
`pest` — falliva su **ogni modulo** che avesse anche una sola classe risolvente
`XotBaseListRecords` nel proprio albero di analisi. Per questo un audit ingenuo
per-modulo di `phpstan analyse Modules/{X}` mostrava lo stesso conteggio di errori
quasi identico su decine di moduli scorrelati: non erano errori distinti, era lo
stesso fatal bootstrap ripetuto.

## Causa

`XotBaseListRecords extends Filament\Resources\Pages\ListRecords` (che dichiara
`public $tableSearch = '';`, ereditata via `Filament\Tables\Concerns\CanSearchRecords`)
e contemporaneamente `use HasXotTable`, che ridichiarava
`protected mixed $tableSearch = null;`.

PHP non permette la risoluzione conflitti su **proprietà** di trait come fa per i
metodi (`insteadof`/`as`): se una classe eredita una proprietà da un genitore E un
trait usato nella stessa classe dichiara la stessa proprietà con visibilità/tipo/
default diversi, è un fatal error di composizione, non un warning.

Verificato che il conflitto si applica anche a:
- `XotBaseManageRelatedRecords` (extends `Filament\Resources\Pages\ManageRelatedRecords`,
  stessa dichiarazione `public $tableSearch = '';`)
- `XotBaseTableWidget` (extends `Filament\Widgets\TableWidget`, via
  `InteractsWithTable` → `CanSearchRecords`)

Non si applica a:
- `XotBaseRelationManager` (extends Filament `RelationManager`, che usa
  `InteractsWithRelationshipTable` — **non** dichiara `$tableSearch` da nessuna parte)
- `XotBaseResourceTable` (classe standalone, nessun extends)

## Fix

Rimossa la dichiarazione della proprietà da `HasXotTable` — il trait ora si affida
esclusivamente a `$this->tableSearch` (ereditata da Filament dove presente).
Verificato via PHPStan scoped che le classi che NON ereditano la proprietà da
Filament (`XotBaseRelationManager`, `XotBaseResourceTable`) non generano errori
"undefined property" — nessun'altra dichiarazione conflittuale nella loro gerarchia.

```php
// PRIMA (in HasXotTable)
protected mixed $tableSearch = null;

// DOPO: nessuna dichiarazione — si usa $this->tableSearch ereditata da Filament
// dove esiste (ListRecords, ManageRelatedRecords, TableWidget); nelle classi
// senza quella proprietà Filament (RelationManager) non viene mai letta prima
// di essere scritta da un binding Livewire, quindi nessun errore.
```

Corretto anche un `MissingImport` PHPMD collaterale nella stessa sessione di verifica
(riga con `throw new \RuntimeException(...)` → `use RuntimeException;` + rimozione
del backslash), rilevato eseguendo `phpmd` sul file come da protocollo post-modifica.

## Perché il conteggio per-modulo era fuorviante prima di questo fix

`./vendor/bin/phpstan analyse Modules/{X}` senza bootstrap SQLite in-memory E con
questo fatal ancora presente restituiva un output quasi identico (poche righe,
stack trace del fatal) per decine di moduli scorrelati — **non** un vero conteggio
di errori statici per modulo. Dopo questo fix, lo stesso comando su `Modules/HR`
(scelto come test) passa da fatal a `[OK] No errors` (a parte l'avviso non
correlato "Ignored error pattern ... was not matched", che è un artefatto di
scoping quando si analizza un singolo modulo con `ignoreErrors` definiti a livello
di intero progetto — non un errore reale, sparisce analizzando l'intero `Modules/`).

## Verifica

```bash
cd laravel
DB_CONNECTION=sqlite DB_DATABASE=:memory: ./vendor/bin/phpstan analyse Modules/HR --memory-limit=-1
# → [OK] No errors (era: PHP Fatal error)

./vendor/bin/pint Modules/Xot/app/Filament/Traits/HasXotTable.php
./tools/phpmd.sh Modules/Xot/app/Filament/Traits/HasXotTable.php text cleancode,codesize,design,naming,unusedcode
DB_CONNECTION=sqlite DB_DATABASE=:memory: ./vendor/bin/pest Modules/Xot/tests/Unit/HasXotTableTest.php
```

## Nota su PHPInsights

`phpinsights analyse` su questo file segnala debito architetturale **preesistente**
non introdotto da questo fix (complessità ciclomatica della classe/metodi, funzioni
oltre 20 righe, divieto generale di trait, alcune righe oltre 80/100 caratteri).
Non toccato in questa sessione: il fix qui documentato è mirato al conflitto fatal
di proprietà, un refactor strutturale del trait è un lavoro separato e più ampio.

## Canon / collegamenti

- `docs/chat/phpstan-modules-zero.md` — swarm coordination, cita questo blocco
- `Modules/Xot/docs/filament/hasxtable-visibility-fix.md` — fix di visibilità
  precedente sullo stesso trait (metodo, non proprietà — problema distinto)
- `Modules/Xot/docs/filament/xot-table.md`

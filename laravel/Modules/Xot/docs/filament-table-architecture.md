---
title: "Dove si configura la tabella di una Resource Filament"
type: guideline
module: Xot
<<<<<<< HEAD
<<<<<<< HEAD
tags: [filament, table, resource, architecture, migration]
created: 2026-09-01
updated: 2026-09-02
qmd: "tabella filament resource table class getTableFilters XotBaseResourceTable HasXotTable list page xot"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/79"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/19"
related:
  - ./wiki/phpstan-best-practices.md
  - ../../../../docs/stories/2.3.deep-quality-enabled-modules.story.md
=======
updated: 2026-09-01
qmd: "tabella filament resource table class getTableFilters XotBaseResourceTable HasXotTable list page xot"
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
=======
updated: 2026-09-01
qmd: "tabella filament resource table class getTableFilters XotBaseResourceTable HasXotTable list page xot"
=======
tags: [filament, table, resource, architecture, migration]
created: 2026-09-01
updated: 2026-09-02
qmd: "tabella filament resource table class getTableFilters XotBaseResourceTable HasXotTable list page xot"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/79"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/19"
related:
  - ./wiki/phpstan-best-practices.md
  - ../../../../docs/stories/2.3.deep-quality-enabled-modules.story.md
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
---

# La tabella si configura nella Table class, non nella pagina

Vale per ogni Resource di questo modulo.

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 28b0298a (fix: phpstan issues)
## Stato della migrazione nel progetto WorkOrder

Il resolver `XotBaseResource::getTableClass()` è nuovamente operativo e il gate
PHPStan globale è verde. Il test architetturale ha però censito debito runtime
ancora esplicito: 13 Resource Timber e `WorkOrderStatusResource` ereditano il
`table()` base ma conservano colonne/filtri/azioni nelle vecchie List page e non
hanno ancora una classe `Tables\*Table`.

Il debito non va nascosto con una tabella vuota o uno skip: la migrazione consiste
nello spostare quegli hook nelle Table class dedicate. È tracciata nella story
BMAD 2.3 e resta distinta dal gate PHPStan, che analizza correttamente il contratto
ma non può provare l'esistenza di ogni classe risolta a runtime.

<<<<<<< HEAD
=======
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
=======
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
## La regola

Colonne, filtri e azioni di una Resource stanno in
`app/Filament/Resources/<Nome>Resource/Tables/<Plurale>Table.php`, che estende
`Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable`.

**Non** nella pagina `Pages/List<Plurale>.php`. Quello che scrivi li' dentro non viene
letto da nessuno.

```php
namespace Modules\Xot\Filament\Resources\FooResource\Tables;

use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class FoosTable extends XotBaseResourceTable
{
    /** @return array<string, Column> */
    public function getTableColumns(): array { return [...]; }

    /** @return array<string|int, BaseFilter> */
    public function getTableFilters(): array { return [...]; }
}
```

## Il percorso, per intero

```
List<Plurale>  (estende XotBaseListRecords)
  -> Filament: ListRecords::table()
    -> <Nome>Resource::table()            (XotBaseResource)
      -> ::getTableClass()                risolve <Plurale>Table
        -> XotBaseResourceTable::configure()
          -> HasXotTable::table()          legge getTableColumns/Filters/Actions
```

`XotBaseListRecords` **non usa** `HasXotTable`. Se lo usasse, definirebbe `table()` sulla
pagina, e in Filament il `table()` della pagina vince su quello della Resource: la Table
class non verrebbe mai eseguita. E' esattamente quello che succedeva fino al 1 settembre
2026, con 166 classi `*Table` scritte e mai chiamate.

## I nomi dei metodi non hanno prefisso

Si chiamano `getTableColumns()`, `getTableFilters()`, `getTableActions()`,
`getTableBulkActions()`, `getTableHeaderActions()`, `getTableHeading()`,
`getTableFiltersLayout()`, `getTableRecordActionsPosition()`,
`getTableSortColumn()`, `getTableSortDirection()`.

**Nessun `resolve*`** — `table()` chiama gli hook direttamente; l'override PHP basta
(story 5.53).

**Sort** — `$this->getTableSortColumn()` / `$this->getTableSortDirection()` come gli altri
hook; default su `XotBaseResourceTable`, niente `initial*` / `buildDefault*` nel trait
(story 5.55).

**Niente costanti hardcoded in `table()`** — layout e posizioni sono hook override
nella Table class (default nel trait: filtri `AboveContent`, azioni riga `BeforeColumns`).

**`getXotTableFilters()` e i suoi fratelli non esistono piu'.** Erano nati per non
collidere con i metodi omonimi e deprecati di `Filament\Resources\Pages\ListRecords`,
quando il trait stava sulla pagina. Da quando sta solo su `XotBaseResourceTable` — che e'
una classe normale, non una pagina Filament — la collisione non c'e' e il prefisso non ha
piu' ragione di esistere.

Il prefisso non era neutro: `table()` chiamava il nome prefissato mentre le sottoclassi
overridavano quello semplice, quindi ogni override veniva **ignorato in silenzio**. Nessun
errore, nessun warning, nessun test rosso: solo filtri che non compaiono.

## Il nome della Table class

`getTableClass()` prova due candidati, in quest'ordine:

1. dal **model**: `Str::plural(class_basename(getModel())).'Table'`;
2. dalla **Resource**: `Str::plural(<Resource senza il suffisso Resource>).'Table'`.

Il secondo esiste perche' i file seguono la Resource e non sempre il model: `PesiResource`
ha `PesisTable` ma il suo model e' `Peso`, e `SettlementResource` non dichiara `$model`
affatto. Senza quel candidato si finiva nel fallback per model, che su alcune Resource
restituiva la Table class **di un altro modulo**.

Se nessuno dei due esiste, `getTableClass()` alza `LogicException`: una Resource senza
Table class non degrada a tabella vuota, va in errore. Il contratto e' tenuto da
`Modules/Xot/tests/Unit/ListPageHasTableClassTest.php`, che percorre tutte le list page
concrete del progetto.

## Come accorgersi che si sta sbagliando

Il sintomo e' sempre lo stesso: **la pagina si apre, la tabella c'e', i filtri no**.
Nessun errore da nessuna parte.

```bash
# la Table class che verra' usata davvero
php artisan tinker --execute="echo <Nome>Resource::getTableClass();"
```

Se quello che vedi a schermo non corrisponde a quel file, stai modificando il file
sbagliato.

## Storia, per non ripeterla

- **19 agosto 2026** — rename da `getTableX()` a `getXotTableX()` per schivare la
  deprecazione Filament. Fatto solo dentro `table()`: le sottoclassi restano al nome
  vecchio e diventano mute. `getTableColumns` fu l'unico salvato, con un adapter a
  reflection.
- **1 settembre 2026** — segnalazione utente: i filtri non compaiono su
  `/indennitaresponsabilita/admin/scheda-dips`. La catena reale passava per
  `BaseListSchedas::getTableFilters()`, col nome vecchio.
- **1 settembre 2026** — `use HasXotTable;` tolto da `XotBaseListRecords`, prefisso `Xot`
  rimosso dai nomi, candidato per-Resource in `getTableClass()`, test di regressione.

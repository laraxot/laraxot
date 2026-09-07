---
id: hasxottable-filterslayout-overridable-hook
slug: hasxottable-filterslayout-overridable-hook
scope: [module:Xot, project:base_workorder_fila5]
status: superseded
superseded_by: hasxottable-recordactionsposition-and-filterslayout-hardcode-fix.story.md
priority: Medium
created: 2026-09-07
---

## SUPERSEDED 2026-09-07

A sibling session wrote a more thorough story for the same fix
(`hasxottable-recordactionsposition-and-filterslayout-hardcode-fix.story.md`)
covering everything below plus 3 additional findings (dead-code candidates
`getSearchableColumns()`/`hasSearch()`, a commented-out
`notifyTableMissing()`/`configureEmptyTable()` block, and an integration-test
gap). Kept per no-delete policy. One unique contribution from this story not
in theirs: the `stubTableChain()` missing-`deferFilters` test fix — added as
an addendum to their story.

## Problema

Utente: in `HasXotTable::table()` era meglio
`->recordActionsPosition($this->getTableRecordActionsPosition())` invece di
un `RecordActionsPosition::BeforeColumns` cablato — per permettere a
resource/widget di sovrascrivere il valore per-tabella, come gia' avviene
per `recordTitleAttribute`, `heading`, `columns`, `filters`,
`filtersFormColumns`, `deferFilters`, `recordActions`, `toolbarActions`,
`emptyStateActions`, `paginated`.

## Investigazione

Verificato: `getTableRecordActionsPosition()` gia' esisteva (public,
overridable, default `RecordActionsPosition::BeforeColumns`) e il chiamante
gia' usava `$this->getTableRecordActionsPosition()` — fix gia' applicato da
una sessione gemella nel momento in cui ho controllato. Confermato con
`phpstan analyse Modules/Xot` (0 errori) e test dedicato
`tests/Unit/HasXotTableLayoutHooksTest.php` (gia' presente, gia' verde).

**Altra miglioria proposta e implementata** (richiesta esplicita
dell'utente: "proponi anche altre migliorie"): nello stesso blocco
`table()`, `->filtersLayout(FiltersLayout::AboveContent)` era l'unica
chiamata rimasta cablata a un valore letterale invece di passare per un
hook `getTableXxx()`, disallineata dal resto del metodo. Aggiunto
`getTableFiltersLayout(): FiltersLayout` (stesso pattern esatto di
`getTableRecordActionsPosition()`), chiamato come
`->filtersLayout($this->getTableFiltersLayout())`.

**Migliorie proposte ma NON implementate ora** (fuori scope, comportamento
non banale, da story separata se voluta):

1. `->striped()` e `->persistFiltersInSession()` sono le uniche due chiamate
   booleane rimaste senza un hook `shouldTableXxx()` (esiste gia' il
   precedente `shouldDeferTableFilters()`). Basso rischio, bassa priorita'
   (nessun resource ha mai avuto bisogno di disattivarle finora).
2. `tests/Unit/HasXotTableSortHooksTest.php` — test PRE-ESISTENTE (non
   toccato in questa sessione, gia' falliva prima) che descrive un'API
   `getTableSortColumn()`/`getTableSortDirection()` su
   `XotBaseResourceTable` con default intelligente (`"<tabella>.id"` +
   `desc`, derivato da `getModelClass()`) — funzionalita' MAI implementata,
   diversa da `getDefaultTableSortColumn()`/`getDefaultTableSortDirection()`
   gia' esistenti (che di default restituiscono `null`, cioe' "nessun sort
   di default"). Implementarla cambierebbe il comportamento di ordinamento
   di default per OGNI resource che estende `XotBaseResourceTable` senza un
   sort esplicito — decisione di prodotto, non un fix meccanico. Segnalato,
   non implementato senza conferma esplicita.

## Fix applicati

- `getTableFiltersLayout(): FiltersLayout` aggiunto (default
  `FiltersLayout::AboveContent`, invariato a comportamento pre-esistente).
- `->filtersLayout(FiltersLayout::AboveContent)` → `->filtersLayout($this->getTableFiltersLayout())`.
- `tests/Unit/HasXotTableTest.php`: `stubTableChain()` non elencava
  `deferFilters` tra i metodi consentiti sul mock — gap pre-esistente
  (probabile omissione quando `shouldDeferTableFilters()`/`deferFilters()`
  furono aggiunti), scoperto rieseguendo la suite dopo il mio edit. Aggiunto
  `'deferFilters'` alla lista.
- Rimosso `app/Filament/Traits/HasXotTable.php.bak` — file di backup non
  tracciato da git, snapshot precedente a `getTableRecordActionsPosition()`,
  nessun contenuto perso (studiato prima di rimuovere, non serviva).

## Verifica

- `phpstan analyse Modules/Xot` (cache pulita): 0 errori.
- `phpmd Modules/Xot/app/Filament/Traits/HasXotTable.php`: 1 finding
  pre-esistente non toccato (`MissingImport` su `\RuntimeException` alla
  riga 426, lontano dall'area modificata).
- `pest Modules/Xot/tests/Unit/HasXotTableTest.php
  Modules/Xot/tests/Unit/HasXotTableLayoutHooksTest.php`: 4 passed (2 nuovi
  green grazie al fix `deferFilters`, 2 gia' verdi e confermati per
  `getTableFiltersLayout`/`getTableRecordActionsPosition`).
- `HasXotTableSortHooksTest.php` resta rosso (2 test), pre-esistente,
  documentato sopra, non nel perimetro di questa story.

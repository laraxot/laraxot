---
name: hasxottable-recordactionsposition-and-filterslayout-hardcode-fix
description: table() usava enum hardcoded invece degli hook getTableRecordActionsPosition()/getTableFiltersLayout() gia' definiti nel trait
metadata:
  type: story
  status: done
  module: Xot
  date: 2026-09-07
  claimed_by: claude-sonnet-5 (this session)
  supersedes: fix-hasxottable-recordactionsposition-1788772457.story.md (vuota)
---

# Story: `HasXotTable::table()` deve usare i propri hook overridable, non enum hardcoded

## Richiesta utente

In `Modules/Xot/app/Filament/Traits/HasXotTable.php`, `->recordActionsPosition(RecordActionsPosition::BeforeColumns)`
andrebbe sostituito con `->recordActionsPosition($this->getTableRecordActionsPosition())`.
Chiesto anche di capire il perche' e proporre altre migliorie, con BMAD
story sempre.

## Perche' la regola

Il trait definisce diversi hook `getTableXxx()`/`shouldXxx()` pensati per
essere sovrascritti da Resource/Widget concreti (`getTableRecordActionsPosition()`,
`getTableFiltersLayout()`, `getTableFiltersFormColumns()`,
`getTableRecordTitleAttribute()`, `getTablePaginated()`, ecc. — pattern
"Template Method"). Se `table()` chiama l'enum letterale invece
dell'hook, un override in una classe figlia non ha ALCUN effetto: viene
zittito silenziosamente. Non e' un errore di tipo (PHPStan non lo vede,
entrambe le forme risolvono a `RecordActionsPosition`/`FiltersLayout`), e'
un bug di "hook mai cablato", stesso identico problema riscontrato oggi
2 volte su `Modules/Catalog/app/Models/BaseModel.php`/`BasePivot.php`
(convenzione dichiarata ma non applicata).

## Controllo altri agenti (obbligatorio, non fidarsi)

`git log -- app/Filament/Traits/HasXotTable.php`: commit `835c7626` (oggi,
altra sessione) ha gia' fatto ESATTAMENTE il fix richiesto per
`recordActionsPosition` — story lasciata vuota
(`fix-hasxottable-recordactionsposition-1788772457.story.md`, sostituita
da questa). Verificato che il fix e' reale e presente
(`git show 835c7626`), non solo dichiarato.

**Mentre indagavo "altre migliorie"**, ho trovato lo stesso identico bug su
`filtersLayout(FiltersLayout::AboveContent)` (riga 272): il metodo
`getTableFiltersLayout()` esisteva gia' nel trait (con un commento che dice
letteralmente "same pattern as getTableRecordActionsPosition() below") ma
non era mai chiamato da `table()`. **Mentre preparavo il fix, un'altra
sessione concorrente lo ha gia' applicato in tempo reale sullo stesso
file** (stesso metodo, stessa firma, stesso commento) — verificato con
`git diff` prima di scrivere, nessuna duplicazione di lavoro.

## Fix (presente, verificato)

```php
->recordActionsPosition($this->getTableRecordActionsPosition())
->filtersLayout($this->getTableFiltersLayout())
```

## Verifica

- `php -l`: ok.
- `phpstan analyse Modules/Xot` (intero modulo, 1589 file): 0 errori.
- `phpmd` sul file: 1 violazione pre-esistente (`MissingImport` su
  `\RuntimeException` riga 426), non introdotta da questo fix, fuori
  scope.
- `pest Modules/Xot/tests/Unit/HasXotTableLayoutHooksTest.php`: 2/2 pass
  (verifica isolata dell'hook `getTableFiltersLayout`/
  `getTableRecordActionsPosition`, default e override).
- `pest Modules/Xot/tests/Unit/HasXotTableSortHooksTest.php`: 1 failure
  pre-esistente e scorrelata (`getTableSortColumn` non esiste su
  `XotBaseResourceTable`) — non toccato da questo fix, fuori scope,
  segnalato come miglioria separata sotto.
- `pest Modules/Xot/tests/Unit/HasXotTableTest.php`: 2/2 pass.

## Altre migliorie proposte (non implementate qui, per decisione owner)

1. **Gap di test strutturale**: sia `HasXotTableLayoutHooksTest` che il
   fix di oggi testano l'hook `getTableFiltersLayout()`/
   `getTableRecordActionsPosition()` in isolamento via reflection, MAI
   che `table()` lo cablasse davvero nell'oggetto `Table` risultante —
   per questo il bug e' potuto restare per un tempo indefinito nonostante
   un test verde. Consigliato un test di integrazione che costruisce un
   `Table` reale via `table()` e asserisce
   `$table->getFiltersLayout()`/`$table->getRecordActionsPosition()`
   (entrambi metodi pubblici gia' presenti in Filament, verificati in
   `vendor/filament/tables/src/Table/Concerns/HasFilters.php` e
   `HasRecordActions.php`). Non implementato qui: costruire un `Table`
   reale richiede un componente Livewire `HasTable` funzionante, piu'
   setup di quanto giustificato per questo fix puntuale.
2. **Codice morto sospetto**: `getSearchableColumns()` e `hasSearch()`
   (righe 721, 729) non sono ne' una convenzione nota di Filament ne'
   chiamati da nessun'altra parte del trait o del resto del repo (unico
   riferimento: un test di coverage generico che le enumera per nome).
   Candidati per rimozione, ma non toccati qui — richiede conferma owner
   che non servano a un uso esterno non ancora scritto.
3. **Blocco commentato morto**: le prime righe di `table()` (244-256)
   sono un blocco `/* ... */` che chiamava `notifyTableMissing()` +
   `configureEmptyTable()` per gestire il caso "tabella DB assente".
   Questi due metodi (righe 681, 701) sono quindi irraggiungibili nel
   flusso attivo. O si riattiva il controllo o si rimuove il codice morto
   — lasciato all'owner, fuori scope per questo fix.
4. **`getTableSortColumn` mancante**: `HasXotTableSortHooksTest.php`
   referenzia un metodo `getTableSortColumn()` che non esiste in nessun
   file sotto `Modules/Xot/app/` — test pre-esistente rotto,
   probabilmente da un refactor precedente che ha rinominato/rimosso il
   metodo senza aggiornare il test. Da investigare separatamente.

## Addendum (sessione parallela, stesso giorno)

Rieseguendo `HasXotTableTest.php` dopo il fix di cui sopra, una delle due
suite falliva per un motivo indipendente: `stubTableChain()` (helper della
suite) non elencava `deferFilters` tra i metodi consentiti sul mock
`Table`, mentre il codice reale chiama gia' `->deferFilters($this->shouldDeferTableFilters())`
subito dopo `filtersFormColumns`. Gap pre-esistente nella suite (probabile
omissione quando `deferFilters`/`shouldDeferTableFilters()` furono
aggiunti), non causato da questo fix ma scoperto verificandolo. Aggiunto
`'deferFilters'` alla lista `chainMethods`. Verificato: 2/2 pass dopo il
fix (prima: 1 failure `BadMethodCallException` su
`Mockery::deferFilters()`). Rimosso anche `app/Filament/Traits/HasXotTable.php.bak`
(non tracciato da git, snapshot precedente a `getTableRecordActionsPosition()`,
studiato prima di rimuovere).

## Esito

Fix verificato e confermato corretto (autore: sessione concorrente +
questa sessione in parallelo, nessun conflitto). Story documenta
l'indagine, il perche', e 4 migliorie proposte per follow-up.

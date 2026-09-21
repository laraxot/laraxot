---
id: story-related-resource-table-vs-parenttable
title: "relatedResourceTable vs parentTable — Analisi della proposta di sostituzione"
descript_type: bmad
scope: module:Xot
status: ready-for-dev
github: {issues: "https://github.com/laraxot/module_xot_fila5/issues/115", discussions: "https://github.com/laraxot/module_xot_fila5/discussions/117"}
---

# relatedResourceTable vs parentTable — Analisi della proposta

## Proposta
Sostituire la property `protected ?Table $relatedResourceTable` con:
```php
$this->relatedResourceTable = $this->parentTable($this->relatedResourceTable);
```
e forse rinominare la property in `$_table` o `$table`.

## Analisi tecnica

### Perché parentTable() non è disponibile
- `parentTable()` è un alias di `HasXotTable::table()` (`use HasXotTable { table as parentTable; }`)
- Nella **settima direzione** (2026-09-11), `HasXotTable` è stato **rimosso intenzionalmente** dalle pagine `XotBaseManageRelatedRecords`
- Motivo: evitare gli `@phpstan-ignore` per i metodi deprecati del trait (misurato ora: 25 ignore totali in `HasXotTable.php`, 13 su `method.deprecated` — non "~78", numero mai verificato e ripetuto per errore in piu' commit/story di questa saga)
- Senza il trait, `parentTable()` non esiste più in questa classe

### Perché $_table non è una buona idea (CORREZIONE 2026-09-11: non per il motivo scritto qui sotto)
- **Falso, verificato** (`grep`/`git log -S '_table' -- app/Filament/Traits/HasXotTable.php`, zero risultati in tutta la storia del file): `HasXotTable` NON ha mai dichiarato una property `$_table`, pubblica o protetta. La frase "e la property `$_table` public che causava l'errore Livewire" sopra e in almeno due commit precedenti di questa stessa saga e' una citazione inventata, mai verificata contro il trait reale.
- Il motivo VERO per evitare `$_table`/restare protected e' generico, non legato a un precedente specifico: Livewire idrata solo proprieta' PUBBLICHE e solo per tipi che sa serializzare; `Filament\Tables\Table` non lo e', quindi qualunque property pubblica di quel tipo (a prescindere dal nome) romperebbe il render con "Property type not supported in Livewire for property: [...]" — vedi correzione nel docblock di classe.
- Rinominare in `$_table` resta comunque sconsigliato (nessun precedente reale con cui collidere, ma nemmeno un motivo per preferirlo a un nome piu' descrittivo)

### Perché $table non funziona
- `$table` è un parametro locale di `table(Table $table): Table`
- Non persiste tra una chiamata e l'altra ( Livewire serializza le property di istanza, non i parametri locali)
- I 5 hook (`getTableColumns()`, ecc.) sono chiamati separatamente da Filament, quindi hanno bisogno di una property di istanza

### Perché relatedResourceTable è la scelta corretta
- `protected ?Table $relatedResourceTable = null` — protetta, mai serializzata da Livewire
- Unica fonte dei default dei 5 hook
- Nome descrittivo (dice cosa contiene: la tabella della Resource CORRELATA), non scelto per evitare una collisione reale (vedi correzione sopra: quella collisione non esiste)
- Documentata nel docblock di classe con la spiegazione del vincolo Livewire (generico, non legato a un precedente specifico)

## Decisione
Mantieni `relatedResourceTable` così com'è. La proposta di usare `parentTable()` richiederebbe di ri-agganciare `HasXotTable`, invertendo il design 7 e reintroducendo i problemi risolti nell'audit dei 12 errori.

## Alternative considerate
1. **Rinominare in `relatedTable`** — più breve, ma guadagna poco
2. **Rimuovere la property e ricalcolare ogni volta** — inefficiente, le chiamate a `$resourceClass::table()` sarebbero duplicate
3. **Usare un static** — peggiorerebbe il testability

## Acceptance Criteria
- [ ] La property rimane `protected ?Table $relatedResourceTable = null`
- [ ] Nessun riferimento a `parentTable()` o `HasXotTable` in questa classe
- [ ] Documentazione aggiornata

## GitHub
- Issue #115: https://github.com/laraxot/module_xot_fila5/issues/115
- Discussion #117: https://github.com/laraxot/module_xot_fila5/discussions/117
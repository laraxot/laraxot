# BMAD: Documentazione corretta — analisi reale di XotBaseManageRelatedRecords

## Riepilogo

Il file `XotBaseManageRelatedRecords.php.md` descriveva un'architettura proposta che NON corrispondeva al codice reale. Questa story documenta gli errori trovati, la documentazione corretta prodotta e i prossimi passi.

## Errori trovati nel `.md` precedente

### 1. Metodi inesistenti nel file `.php` reale
Il `.md` documentava:
- `getTableColumns()` — **NON esiste** nella classe `XotBaseManageRelatedRecords` (è astratto in `HasXotTable`)
- `getTableHeaderActions()` — **NON esiste** nella classe (è in `HasXotTable`)
- `$_table` come property della classe — **NON esiste** (è in `HasXotTable` come `public Table $_table`)
- `configureRelatedTable()` — **mai esistito** nel codebase

### 2. Logica `table()` completamente sbagliata
Il `.md` mostrava:
```php
$this->_table = $resourceClass::table($table);
return $this->_table
    ->columns($this->getTableColumns())
    ->headerActions($this->getTableHeaderActions());
```
Ma il **codice reale** è:
```php
if (static::getRelatedResource() === null) {
    $resourceClass = $this->getRelatedResourceClass();
    $table = $resourceClass::table($table);
    $table = $this->parentTable($table);  // alias HasXotTable::table()
}
return $table;
```

### 3. Uso di `HasXotTable` documentato come NON presente
Il `.md` suggeriva di non usare `HasXotTable` (tranne per `parentTable`). Il codice reale usa `use HasXotTable { table as parentTable; }` — il trait è **eccentricamente ereditato**.

### 4. Bug Livewire non documentato
`HasXotTable` dichiara `public Table $_table;` che Livewire non riesce a serializzare (errore `Property type not supported in Livewire for property: [{}]`). Questo è un bug residuo ereditato, documentato in `18.23-list-page-hook-tabella-morti-dopo-hasxottable` e `18.27-hasxottable-fuori-dai-componenti-filament`.

### 5. Proposta mai implementata
Il `.md` proponiva una quinta direzione che non è mai stata implementata nel codice reale.

## Documentazione corretta prodotta

Il file `.md` è stato aggiornato per documentare:
- L'algoritmo reale (controllo `getRelatedResource() === null`, delega alla Resource correlata, alias `parentTable`)
- I 22+ metodi forniti da `HasXotTable` e perché il trait con alias è indispensabile (evita di reimplementarli tutti)
- L'errore Livewire (public `Table $_table` non serializzabile)
- La distinzione tra ciò che esiste e ciò che è solo proposta

## Prossimi passi (documentazione, non implementazione)

1. [ ] Verificare con il maintainer se l'errore Livewire (`public Table $_table` in `HasXotTable`) è intenzionale o un bug da correggere
2. [ ] Aggiornare gli altri `.md` che referenziano l'architettura proposta errata (es. `xotbasemanagerelatedrecords-convention-over-configuration.story.md` — verificare riferimenti)
3. [ ] Creare story per documentare la scelta di NON implementare la quinta direzione (proposta scartata)
4. [ ] Aggiornare `docs/index.md` se necessario per riflettere la correzione

## GitHub Tracking

- Issue #115 / Discussion #117 — per la decisione architetturale
- Issue #112 / Discussion #114 — secondo filone parallelo (stessa decisione, non riconciliato)

## Evidenza

- File reale analizzato: `Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
- Trait analizzato: `Modules/Xot/app/Filament/Traits/HasXotTable.php` (linea 69: `public Table $_table;`)
- Documentazione corretta: `Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`
- Story collegata: `Modules/Xot/docs/stories/xotbase-manage-related-records-architecture-mismatch.story.md` (aggiornata)

## Conclusione

La documentazione ora riflette accuratamente il codice reale. Il `.md` precedente descriveva una proposta mai implementata con errori factuali. La documentazione corretta ora spiega l'algoritmo reale, il motivo dell'uso di `HasXotTable` con alias, e il bug Livewire noto.
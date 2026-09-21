---
id: story-table-delegation-column-composition
title: "Story: table() delegation and getTableColumns() composition"
description: "Documenta l'algoritmo di delega table() in XotBaseManageRelatedRecords: $resourceClass::table($table) configura tutto; getTableColumns() sovrascrive le colonne; ...parent::getTableColumns() estende."
document_type: story
category: bmad
scope: module:Xot
status: ready-for-dev
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
tags: [bmad, story, filament, table, delegation, gettablecolumns, template-method, columns]
related:
  - ../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md
  - ../../app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php
---

# Story: table() delegation and getTableColumns() composition

## Contesto
La classe `XotBaseManageRelatedRecords` gestisce le pagine di record correlati (es. `ManageContacts`). Il metodo `table()` è il punto di ingresso per la configurazione della tabella.

## L'algoritmo di delega

### Flusso di `table()`

```php
public function table(Table $table): Table
{
    $this->_table = $table;
    if (static::getRelatedResource() === null) {
        $resourceClass = $this->getRelatedResourceClass();  // 1. Risolve ContactResource
        $table = $resourceClass::table($table);              // 2. Configura TUTTO dalla Resource
        $this->tableColumns = $table->getColumns();          // 3. Memorizza le colonne
        $table = $this->parentTable($table);                 // 4. Applica HasXotTable (sigillato)
    }
    return $table;
}
```

### Cosa fa `$resourceClass::table($table)`
Questa chiamata statica esegue il `table()` della Resource correlata (es. `ContactResource`). Internamente, il `table()` della Resource:
1. Chiama `$this->getTableColumns()` → imposta le colonne.
2. Chiama `$this->getTableHeaderActions()` → imposta le azioni header.
3. Chiama `$this->getTableFilters()` → imposta i filtri.
4. Chiama `$this->getTableActions()` → imposta le azioni riga.
5. Chiama `$this->getTableSearchColumn()` → imposta la ricerca.
6. Chiama `$this->getTableSort()` → imposta l'ordinamento.
7. ...e tutto il resto.

**Risultato**: la `$table` è completamente configurata dalla Resource correlata.

### Cosa fa `$this->parentTable($table)`
Questa chiamata applica il metodo `table()` di `HasXotTable` (trait sigillato, mai sovrascritto dai consumer). Questo metodo:
1. Legge `$this->getTableColumns()` **della pagina** (se sovrascritto).
2. Applica le colonne della pagina alla `$table` (sovrascrive quelle impostate da `ContactResource::table()`).
3. Applica le azioni header, i filtri, le azioni riga, ecc. dalla pagina.

**Risultato**: se la pagina ha sovrascritto `getTableColumns()`, le sue colonne **sostituiscono** quelle della Resource.

## Contratto di getTableColumns()

| Caso | Comportamento |
|------|---------------|
| **Nessun override** | Restituisce le colonne della Resource correlata (es. `ContactResource` → `nome`, `email`, `telefono`, ...). |
| **Override con 1 colonna** | Mostra **solo** quella colonna (sostituzione totale, non merge). |
| **Override con `...parent::getTableColumns()`** | Mostra le colonne personalizzate **più** quelle della Resource correlata (aggiunta esplicita). |

## Regola d'oro

- **Default additivo, mai distruttivo**: un override può **estendere** il risultato di `parent::getTableColumns()` ma non può rimuovere colonne senza rimuoverle esplicitamente dall'array restituito.
- **Nessuna magia implicita**: il padre non esegue alcun merge automatico; l'estensione è responsabilità dello sviluppatore.
- **`configureRelatedTable()` è per le azioni**, non per le colonne: quel metodo è riservato alle azioni header specifiche della pagina (create, associate, import).

## Esempi di codice (illustrativi)

### 1. Nessun override → tutte le colonne della Resource
```php
class ManageContacts extends XotBaseManageRelatedRecords { /* niente getTableColumns() */ }
```

### 2. Override con 1 colonna → solo quella colonna
```php
protected function getTableColumns(): array
{
    return ['pippo' => TextColumn::make('pippo')];
}
```

### 3. Override che estende → colonne personalizzate + tutte quelle del padre
```php
protected function getTableColumns(): array
{
    return [
        'pippo' => TextColumn::make('pippo'),
        ...parent::getTableColumns(), // ← aggiunge le colonne della Resource correlata
    ];
}
```

## Acceptance criteria
- [ ] AC1: senza override, la tabella mostra tutte le colonne della Resource correlata.
- [ ] AC2: con override che restituisce 1 colonna, la tabella mostra esattamente quella colonna.
- [ ] AC3: con override che usa `...parent::getTableColumns()`, la tabella mostra le colonne personalizzate + quelle della Resource.
- [ ] AC4: nessun cambiamento di comportamento nelle pagine cross-modulo (`ManagePdfStyle`, `ManageCharts`) – continuano a mostrare `[]` se non sovrascrivono il metodo.
- [ ] AC5: `phpstan analyse` repo-wide → `[OK] No errors`.

## Note
- Questo contratto è già presente implicitamente in `HasXotTable` e `XotBaseResourceTable` (Template Method, `table()` sigillato).
- Non si deve usare `configureRelatedTable()` per le colonne – quel metodo è riservato alle azioni header.
- In futuro, `configureRelatedTable()` sarà rimosso e le azioni header saranno spostate in `getTableHeaderActions()` (già esistente in `HasXotTable`).

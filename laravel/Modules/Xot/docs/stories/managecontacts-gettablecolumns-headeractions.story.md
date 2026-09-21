---
id: story-gettablecolumns-gettableheaderactions
title: "Story: getTableColumns() and getTableHeaderActions() — Composition vs. Configuration"
description: "Documenta il design contract di getTableColumns() e getTableHeaderActions() in XotBaseManageRelatedRecords: composizione delle colonne, hook separato per le azioni, e la filosofia del Template Method Pattern."
document_type: story
category: bmad
scope: module:Xot
status: ready-for-dev
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
tags: [bmad, story, filament, gettablecolumns, gettableheaderactions, template-method, columns, header-actions]
related:
  - ../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md
  - ../../app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php
---

# Story: getTableColumns() and getTableHeaderActions()

## Contesto

La classe `XotBaseManageRelatedRecords` (XotBaseManageRelatedRecords.php) gestisce le pagine di record correlati in Filament. Il design segue il **Template Method Pattern** con due hook ben definiti:

- **`getTableColumns()`** → definisce le colonne della tabella
- **`getTableHeaderActions()`** → definisce le azioni contestuali (create, associate, import)

## Il contratto

### 1. getTableColumns() — Colonne della tabella

- **Default**: se la `relatedResource` è risolta, delega a `app($resourceClass::getTableClass())->getTableColumns()` → **tutte le colonne della Resource correlata**.
- **Default**: se `relatedResource` è `null`, restituisce `[]` → **nessuna colonna** (comportamento attuale di pagine cross-modulo come `ManagePdfStyle`, `ManageCharts`).

**Regola d'oro**: *aggiunta, mai sostituzione*. Un override in una sottoclasse (es. `ManageContacts`) può **estendere** il default, non sostituirlo.

### 2. getTableHeaderActions() — Azioni contestuali

- Restituisce azioni specifiche per la pagina (es. `create`, `associate`, `import`).
- Non è una colonna, ma un'azione di interazione con la pagina.
- Usa il hook esistente `getTableHeaderActions()` di `HasXotTable` — **non** `configureRelatedTable()`.

### 3. getRelatedResourceClass() — Risoluzione della Resource correlata

- Prima: `getRelatedResource()` esplicito (es. `ManageContacts`).
- Altrimenti: convenzione per nome (`Modules\<Module>\Filament\Resources\<Model>\Resource`).
- **NON** usa `Filament::getModelResource()` (evita problemi cross-pannello).

## Il Template Method Pattern (Filament)

- `table()` è un **metodo sigillato** (never overridden) che costruisce la tabella completa.
- `getTableColumns()` e `configureRelatedTable()` sono **hook polimorfici** che le pagine possono sovrascrivere.
- `HasXotTable::table()` è il Template Method sigillato che chiama questi hook in ordine.

## Perché questa architettura?

| Problema | Soluzione |
|----------|-----------|
| Colonne mancanti in pagine cross-modulo | `getTableColumns()` restituisce `[]` solo quando `relatedResource` è `null` |
| Azioni header mancanti | `configureRelatedTable()` o `getTableHeaderActions()` fornisce azioni specifiche |
| Override ridondante | `HasXotTable::table()` è sigillato — le pagine devono sovrascrivere solo `getTableColumns()`/`configureRelatedTable()`/`getFormSchema()` |
| Configurazione conflittuale | `getTableColumns()` gestisce solo le colonne, `configureRelatedTable()` gestisce solo le azioni header |

## Impatto atteso

- **Nessuna pagina cambia comportamento** senza rimuovere gli override esistenti (es. `ManageContacts` ha già override).
- Pagine cross-modulo (`ManagePdfStyle`, `ManageCharts`) continueranno a mostrare `[]` per colonne.
- Pagine con `$relatedResource` risolto (es. `ManageContacts`) mostreranno le colonne della `SurveyPdfResource` (es. `pippo`, `nome`, `email`, ...).

## Checklist per implementazione

- [ ] Rimuovere `configureRelatedTable()` (dead code)
- [ ] Assicurarsi che `getTableColumns()` e `getTableHeaderActions()` siano i **unici** punti di ingresso per colonne/azioni
- [ ] Verificare che pagine cross-modulo continuino a mostrare `[]` per colonne
- [ ] Testare `ManageContacts` con replay HTTP (10 righe, azioni Associare/Importa) → 200, 0 eccezioni

## Referenze

- `XotBaseManageRelatedRecords.php` — classe principale (stato v4 ibrido)
- `XotBaseResource.php` — risoluzione della `relatedResource` via `getRelatedResourceClass()`
- `HasXotTable.php` — `table()` sigillato, `getModelClass()` per il modello della relazione
- `HasXotForm.php` — `form()` delega a `getRelatedResourceClass()` → `getFormSchema()`
```

### 2. `/var/www/_bases/base_quaeris_fila5/laravel/Modules/Xot/docs/stories/gettablecolumns-headeractions.story.md`

```markdown
---
id: story-managecontacts-gettablecolumns-headeractions
title: "Story: ManageContacts — getTableColumns() + getTableHeaderActions() (via configureRelatedTable())"
description: "Gestisce il comportamento atteso di ManageContacts: colonne tramite getTableColumns(), azioni header tramite configureRelatedTable() (da migrare a getTableHeaderActions() in futuro)."
document_type: story
category: bmad
scope: module:Quaeris
status: ready-for-dev
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
tags: [bmad, story, filament, managecontacts, gettablecolumns, gettableheaderactions, configurerealtable]
related:
  - ../app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php
  - ../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md
---

# Story: ManageContacts – colonne e azioni header

## Contesto
`ManageContacts` è la pagina che mostra i contatti collegati a un `SurveyPdf`. Attualmente usa:
- `getTableColumns()` per definire le colonne della tabella.
- `configureRelatedTable()` per definire le azioni header (create, associate, import) con `survey_pdf_id` dell'owner.

## Contratto atteso

### Colonne (`getTableColumns()`)
- **Nessun override**: mostra tutte le colonne di `ContactResource` (es. `nome`, `email`, `telefono`, `pippo`, ...).
- **Override con 1 colonna**: mostra solo quella colonna (es. solo `pippo`).
- **Override con `...parent::getTableColumns()`**: mostra le colonne personalizzate + quelle di `ContactResource`.

### Azioni header (`configureRelatedTable()` – da migrare)
- Attualmente restituisce:
  ```php
  return $table->headerActions([
      'create' => CreateAction::make(),
      'associate' => AssociateAction::make(),
      'import' => ImportAction::make()
          ->importer(ContactImporter::class)
          ->options([
              'survey_pdf_id' => $owner->getKey(),
          ]),
  ]);
  ```
- Queste azioni sono **specifiche della pagina** (legate all'owner `survey_pdf_id`) e non sono parte della Resource `Contact` in generale.

## Perché non usare `configureRelatedTable()` per le colonne?
- `configureRelatedTable()` è chiamato **dopo** che `table()` ha già configurato la tabella dalla Resource.
- Usare `configureRelatedTable()` per le colonne sarebbe un **abuso del pattern**: mescolerebbe due concetti (colonne vs azioni) in un solo hook.
- Il corretto approccio è:
  - Colonne → `getTableColumns()` (solo questo metodo deve restituire l'array di colonne)
  - Azioni header → `getTableHeaderActions()` (hook esistente in `HasXotTable`, non `configureRelatedTable()`)

## Perché `getTableColumns()` con 1 colonna mostra solo 1 colonna

- Se `ManageContacts` sovrascrive `getTableColumns()` con `['pippo' => ...]`, **sostituisce** le colonne della `ContactResource` con solo `pippo`.
- Per vedere le altre colonne, deve usare `...parent::getTableColumns()` per estendere il default.

## Perché `getTableHeaderActions()` è il hook giusto

- `getTableHeaderActions()` è un metodo **già esistente** in `HasXotTable` — non richiede modifiche alla struttura base.
- È già usato da pagine come `ManageContacts` per le azioni header.
- `configureRelatedTable()` è **dead code** in questo contesto: il suo unico scopo era gestire le azioni, ma ora queste azioni sono esposte tramite `getTableHeaderActions()`.

## Aggiornamento futuro (2026-09-11)

- In una story separata, `configureRelatedTable()` sarà rimosso.
- Le azioni header saranno spostate in `getTableHeaderActions()` (già esistente in `HasXotTable`).
- Questo renderà la classe completamente aderente al Template Method Pattern: nessun override di `table()`/`form()`, solo hook esistenti.

## Checklist per l'implementazione

- [ ] Rimuovere `configureRelatedTable()` (dead code)
- [ ] Assicurarsi che `getTableColumns()` e `getTableHeaderActions()` siano i **unici** punti di ingresso per colonne e azioni
- [ ] Verificare che pagine cross-modulo continuino a mostrare `[]` per colonne
- [ ] Testare `ManageContacts` con replay HTTP (10 righe, azioni Associare/Importa) → 200, 0 eccezioni
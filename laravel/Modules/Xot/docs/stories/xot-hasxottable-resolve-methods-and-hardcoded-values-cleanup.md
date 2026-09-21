---
id: story-xot-hasxottable-resolve-methods-and-hardcoded-values-cleanup
slug: story-xot-hasxottable-resolve-methods-and-hardcoded-values-cleanup
title: "STORY — HasXotTable: via i metodi resolve*, valori hardcoded diventano getter overridabili"
description: "Backlog emerso da un dump accumulato in coda a bashscripts/docs/prompts/start.md (v32->v33, vedi docs/chat/start-md-accumulated-backlog-2026-09-08.md sezioni 6-8): HasXotTable.php ha 8 metodi privati con prefisso resolve* (nessun metodo del progetto dovrebbe iniziare per resolve) e 3 valori hardcoded (FiltersLayout::AboveContent, RecordActionsPosition::BeforeColumns, gia' due sort column/direction passati tramite resolve*) che dovrebbero essere getter overridabili dalle classi figlie, coerente con lo stile del resto del trait."
document_type: story
category: bmad
scope: module:Xot
github_id: module_xot_fila5#107
status: ready-for-dev
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: medium
created_at: '2026-09-08'
updated_at: '2026-09-08'
tags: [bmad, story, xot, filament, table, dry, kiss]
related:
  - ../../laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php
  - ../../laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseListRecords.php
  - ../../docs/chat/start-md-accumulated-backlog-2026-09-08.md
github:
  repository: https://github.com/laraxot/module_xot_fila5
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# STORY — HasXotTable: via i metodi resolve*, valori hardcoded diventano getter overridabili

## Contesto

`bashscripts/docs/prompts/start.md` aveva accumulato in coda (violando la sua stessa
regola di igiene) una serie di richieste puntuali su `HasXotTable.php`. Estratte e
verificate contro il codice reale in questa sessione — vedi
`docs/chat/start-md-accumulated-backlog-2026-09-08.md` sezioni 3-10 per l'elenco
completo. Di quelle 8 sezioni, verificate una per una contro il codice attuale:

- **Sezione 3** (HasXotFactory non va con HasFactory/newFactory ridondanti): **già
  risolto** — nessun model nel monorepo combina i due (grep cross-modulo a vuoto,
  incluso l'esempio `Media.php` citato nel dump, già corretto).
- **Sezione 5** (rinomina `getXotTableFilters` → `getTableFilters` ecc., 9 metodi):
  **già fatto** — nessuna occorrenza `getXot*` residua nel codice, solo citata come
  esempio "CORRETTO" in `docs/wiki/memories/xot-table-filters-method-name.md`.
- **Sezione 8** (`initialTableSortColumn`/`initialTableSortDirection` → getter
  `getTableSortColumn`/`getTableSortDirection`): **non applicabile** — zero
  occorrenze di quei due nomi in tutto `Modules/`, riferimento a codice che non
  esiste (più) in questo checkout.
- **Sezioni 6 e 7** (metodi `resolve*` da eliminare, 3 valori hardcoded da rendere
  getter): **verificate presenti**, oggetto di questa story.
- **Sezione 4** (`XotBaseListRecords` non deve più avere `use HasXotTable` perché
  c'è già `XotBaseResourceTable`): **deliberatamente fuori scope qui**, vedi Dev
  Notes — non è la stessa cosa della sezione 6/7, è un refactor architetturale a
  raggio cross-modulo con un design ancora da fare.

## Problema

In `laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php`:

1. Il metodo `table()` (righe ~243-266) chiama 8 metodi privati con prefisso
   `resolve*` (`resolveTableFilters`, `resolveTableHeaderActions`,
   `resolveTableActions`, `resolveTableBulkActions`,
   `resolveTableEmptyStateActions`, `resolveTableHeading`,
   `resolveDefaultTableSortColumn`, `resolveDefaultTableSortDirection`) — nessun
   metodo del progetto dovrebbe iniziare per `resolve` (convenzione di naming,
   vedi backlog sezione 6).
2. Nello stesso metodo, 2 valori sono hardcoded invece di passare da un getter
   overridabile dalle classi figlie: `->filtersLayout(FiltersLayout::AboveContent)`
   e `->recordActionsPosition(RecordActionsPosition::BeforeColumns)`.
3. `->headerActions(array_values($this->resolveTableHeaderActions()))` applica
   `array_values()` sull'array delle azioni — se una classe figlia definisce
   `getTableHeaderActions()` con chiavi stringa significative (es. nomi di
   azione), `array_values()` le perde silenziosamente.

## Vincolo scoperto durante l'analisi (importante per l'implementazione)

I metodi `resolve*` **non sono indirezione pura da eliminare senza sostituto**:
delegano tutti a `invokeTableHook(string $hookMethod, mixed $default)` (righe
554-564), che usa Reflection per capire se `$hookMethod` è **davvero overridato**
dalla classe figlia (non solo ereditato dal trait o da Filament) prima di
invocarlo — altrimenti ritorna `$default`. Rimuovere i wrapper `resolve*` e
chiamare direttamente `$this->getTableFilters()` ecc. **cambierebbe
comportamento**: verrebbe sempre invocato il getter, anche quando nessuna classe
figlia lo overrida davvero. Il fix corretto è rinominare il wrapper esterno
(`resolveTableFilters` → `getTableFilters`... ma quel nome è già preso dal
getter pubblico reale, vedi sotto) — **non un semplice trova-e-sostituisci**.

Soluzione proposta: dato che `getTableFilters()` (il getter pubblico reale,
overridabile) esiste già ed è un nome diverso dall'hook stringa passato a
`invokeTableHook()` in altri casi (es. `getDefaultTableSortColumn` è il nome
dell'hook, non del wrapper), la via pulita è **inlineare la chiamata a
`invokeTableHook()` direttamente nel metodo `table()`**, eliminando il livello
di indirezione dei wrapper `resolve*` invece di rinominarli:
```php
->filters($this->invokeTableHook('getTableFilters', []))
```
al posto di:
```php
->filters($this->resolveTableFilters())
// con resolveTableFilters() che internamente fa invokeTableHook('getTableFilters', [])
```
Stesso comportamento (Reflection preservata), un livello di indirezione in meno,
zero metodi `resolve*`.

## Acceptance Criteria

1. In `HasXotTable.php`, nessun metodo con prefisso `resolve` esiste più — le 8
   chiamate in `table()` invocano `invokeTableHook()` direttamente (o un getter
   pubblico dedicato, se più leggibile), preservando la semantica "chiama il
   getter solo se la classe figlia lo overrida davvero".
2. `->filtersLayout(FiltersLayout::AboveContent)` diventa
   `->filtersLayout($this->getTableFiltersLayout())`, con
   `getTableFiltersLayout(): FiltersLayout` nuovo metodo `protected` che
   ritorna `FiltersLayout::AboveContent` di default (comportamento identico per
   chi non overrida, overridabile da chi vuole un layout diverso).
3. `->recordActionsPosition(RecordActionsPosition::BeforeColumns)` diventa
   `->recordActionsPosition($this->getTableRecordActionsPosition())`, stesso
   pattern del punto 2 con default `RecordActionsPosition::BeforeColumns`.
4. `->headerActions(array_values($this->resolveTableHeaderActions()))` non
   applica più `array_values()` — le chiavi stringa dell'array (se presenti)
   sopravvivono fino a Filament.
5. Comportamento invariato per ogni classe che NON overrida i nuovi getter: `cd
   laravel && ./vendor/bin/pest Modules/Xot/tests --no-coverage` verde, nessuna
   Resource esistente cambia aspetto/comportamento a runtime (verifica visiva
   manuale su almeno 2 Resource diverse raccomandata, non bloccante per il
   merge).
6. `cd laravel && php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules`
   → `[OK] No errors`.
7. `./tools/phpmd.sh Modules/Xot` e `./tools/phpinsights.sh Modules/Xot` non
   peggiorano rispetto alla baseline pre-modifica.

## Esplicitamente fuori scope

- Sezione 4 del backlog (`XotBaseListRecords` + `use HasXotTable` +
  `XotBaseResourceTable`): `XotBaseListRecords` (pagina Filament, estende
  `Filament\Resources\Pages\ListRecords`) e `XotBaseResourceTable` (classe
  standalone per il pattern `Tables/<Nome>Table.php extends
  XotBaseResourceTable`, vedi `start.md` §3 "Struttura Filament Resource") sono
  **due consumer paralleli e distinti** di `HasXotTable`, non uno sovrapposto
  all'altro. Togliere il trait da `XotBaseListRecords` richiede prima
  progettare come la pagina delega la configurazione tabella alla classe
  `Tables/<Nome>Table.php` della Resource — un redesign che tocca potenzialmente
  ogni modulo con una `ListRecords` page, non un'eliminazione di trait diretta.
  Da trattare in una story dedicata dopo un'analisi separata.

## Tasks/Subtasks

- [ ] Task 1: sostituire le 8 chiamate `resolve*` in `table()` con chiamate
      dirette a `invokeTableHook()`, eliminare le 8 definizioni dei metodi
      `resolve*` (AC1)
- [ ] Task 2: aggiungere `getTableFiltersLayout()` e
      `getTableRecordActionsPosition()`, sostituire i due valori hardcoded (AC2, AC3)
- [ ] Task 3: rimuovere `array_values()` sulla riga `headerActions` (AC4)
- [ ] Task 4: verifica phpstan + phpmd + phpinsights + pest su `Modules/Xot`
      (AC5, AC6, AC7)

## Dev Notes

- Lock prima di ogni edit: `bash bashscripts/lock/lock.sh
  laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php <task-id> <agent-id>`
  — file centrale, consumato da ogni Resource del monorepo.
- Verificato in questa sessione (2026-09-08): zero model combinano
  `HasXotFactory` con `HasFactory`/`newFactory()` ridondanti (grep cross-modulo
  a vuoto) — sezione 3 del backlog già risolta, non riaprire.
- Verificato: zero occorrenze `getXot*` residue — sezione 5 già fatta.
- Verificato: zero occorrenze `initialTableSortColumn`/`initialTableSortDirection`
  — sezione 8 non applicabile, riferimento a codice inesistente.

### References

- [Source: laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php#L243-L266] — `table()`, le 8 chiamate `resolve*` e i 2 valori hardcoded
- [Source: laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php#L487-L546] — le 8 definizioni `resolve*`
- [Source: laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php#L554-L564] — `invokeTableHook()`, il meccanismo Reflection da preservare
- [Source: docs/chat/start-md-accumulated-backlog-2026-09-08.md] — backlog originale, sezioni 3-10

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- Story creata 2026-09-08, scope ristretto alle sole sezioni 6-7 del backlog
  dopo verifica diretta del codice: sezioni 3, 5, 8 già soddisfatte o non
  applicabili; sezione 4 separata per rischio cross-modulo (vedi "Esplicitamente
  fuori scope"). Non ancora implementata.

### File List

- `laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php` (da modificare)

## GitHub (tracciamento)

| Risorsa | Stato | Link |
|---|---|---|
| Issue (modulo) | aperta | https://github.com/laraxot/module_xot_fila5/issues/107 |

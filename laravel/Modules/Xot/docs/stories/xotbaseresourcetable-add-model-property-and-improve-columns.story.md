---
title: "XotBaseResourceTable: aggiungere proprietà $model e migliorare getTableColumns con schema.org"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-add-model-property-and-improve-columns
status: ready-for-dev
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
status_note: "Story creata per tracciare il lavoro di aggiunta di protected static string $model a tutte le classi XotBaseResourceTable e miglioramento UI/UX con schema.org. Da eseguire in parallelo con subagents per velocizzare l'analisi."
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Filament/Resources/XotBaseResourceTable.php"
  - "laravel/Modules/*/app/Filament/Resources/*Table.php"
related:
  - "../app/Filament/Resources/XotBaseResourceTable.php.md"
  - "./xotbasemanagerelatedrecords-convention-over-configuration.story.md"
  - "https://schema.org/Person"
  - "https://schema.org/Organization"
  - "https://schema.org/Place"
---

# XotBaseResourceTable: aggiungere proprietà $model e migliorare getTableColumns con schema.org

## Story

Come manutentore di Xot, voglio che tutte le classi `XotBaseResourceTable` abbiano
la proprietà `protected static string $model` esplicitamente dichiarata con il
model a cui fanno riferimento, e che `getTableColumns()` sia migliorato con
standard schema.org per una migliore UI/UX, cosi' che il codice sia più chiaro,
auto-documentante e conforme agli standard web.

## Contesto / Baseline

Attualmente molte classi `XotBaseResourceTable` non dichiarano esplicitamente
`protected static string $model`, rendendo difficile capire rapidamente quale
model gestiscono senza leggere il codice. Inoltre, le colonne potrebbero non
rispecchiare i campi attuali del database o potrebbero beneficiare di
miglioramenti UI/UX basati su standard schema.org.

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. Trovare tutte le classi che estendono `XotBaseResourceTable` in tutti i moduli
2. Per ogni classe, aggiungere `protected static string $model = ModelClass::class;`
3. Verificare che i campi in `getTableColumns()` esistano ancora nel database
4. Migliorare UI/UX utilizzando standard schema.org dove applicabile
5. Rimuovere colonne che fanno riferimento a campi non più esistenti
6. Aggiungere colonne per nuovi campi se presenti nel database
7. Aggiornare issue #115 e discussion #117 con i findings
8. Aggiornare second brain con lezioni apprese
9. Sincronizzare ogni modulo con git status

## Esplicitamente fuori scope

- Modifiche a `XotBaseResourceTable.php` base (solo le classi figlie)
- Modifiche ai model stessi
- Modifiche alle migrations
- Cambiamenti architetturali alla struttura Table

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [ ] Task 1 — Trovare tutte le classi che estendono XotBaseResourceTable (subagent 1)
- [ ] Task 2 — Analizzare ogni classe e identificare model mancante (subagent 2)
- [ ] Task 3 — Verificare campi database vs colonne Table (subagent 3)
- [ ] Task 4 — Aggiungere proprietà $model a tutte le classi (subagent 1)
- [ ] Task 5 — Migliorare UI/UX con schema.org (subagent 2)
- [ ] Task 6 — Aggiornare GitHub issue/discussion (subagent 3)
- [ ] Task 7 — Sincronizzare tutti i moduli con git (manuale)
- [ ] Task 8 — Aggiornare second brain

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- Schema.org standard per Person: givenName, familyName, email, telephone
- Schema.org standard per Organization: name, description, url
- Schema.org standard per Place: address, geo, openingHoursSpecification
- [Source: Modules/Xot/app/Filament/Resources/XotBaseResourceTable.php] — Classe base da estendere

## Testing

Da eseguire:
- `vendor/bin/phpstan analyse Modules/*/app/Filament/Resources/*Table.php`
- Verifica visiva delle tabelle migliorate
- Test che i campi esistano nelle migrations corrispondenti

## Dependency Maps

Bloccata da: nessuna
Blocca: miglioramenti UI/UX futuri basati su schema.org

## Owned File/Module Scope

Tutte le classi `*Table.php` in tutti i moduli che estendono `XotBaseResourceTable`

## Learnings from Previous Stories

- Lavoro in parallelo con subagents per velocizzare analisi su larga scala
- Schema.org fornisce standard ben definiti per UI/UX
- Proprietà $model esplicita migliora leggibilità e auto-documentazione

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: Story creata, pronta per esecuzione in parallelo con subagents
- Da eseguire con swarm pattern per massimizzare velocità
- Sincronizzazione git moduli separata richiesta

### File List

- `laravel/Modules/Xot/docs/stories/xotbaseresourcetable-add-model-property-and-improve-columns.story.md` (nuovo)

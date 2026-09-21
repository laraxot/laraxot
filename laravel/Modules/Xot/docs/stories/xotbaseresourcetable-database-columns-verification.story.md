---
title: "XotBaseResourceTable: verifica colonne vs database"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-database-columns-verification
status: ready-for-dev
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
status_note: "Story creata per continuare domani la verifica delle colonne Table vs campi database. 88 classi Table da verificare per assicurare che tutte le colonne dichiarate esistano ancora nel database."
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/*/app/Filament/Resources/*/Tables/*.php"
  - "laravel/Modules/*/database/migrations/"
related:
  - "./xotbaseresourcetable-add-model-property-and-improve-columns.story.md"
  - "./xotbaseresourcetable-schemaorg-ux-improvements.story.md"
---

# XotBaseResourceTable: verifica colonne vs database

## Story

Come manutentore di Xot, voglio verificare che tutte le colonne dichiarate nelle classi
Table esistano ancora nel database, cosi' da evitare errori runtime e mantenere il codice
sincronizzato con lo schema attuale.

## Contesto / Baseline

88 classi XotBaseResourceTable trovate. È necessario verificare che le colonne
dichiarate in `getTableColumns()` corrispondano ai campi effettivi nel database.

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. Per ogni classe Table, identificare il model associato
2. Trovare la migration più recente per quel model
3. Verificare che ogni colonna in getTableColumns() abbia un campo database corrispondente
4. Segnalare colonne che fanno riferimento a campi non più esistenti
5. Segnalare campi database senza colonna corrispondente nella Table
6. Rimuovere colonne obsolete dalle classi Table
7. Aggiungere colonne per nuovi campi database se mancanti
8. Aggiornare issue #115 e discussion #117

## Esplicitamente fuori scope

- Modifiche al database schema
- Modifiche alle migrations
- Ristrutturazione delle tabelle

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [ ] Task 1 — Mappare ogni classe Table al suo model
- [ ] Task 2 — Trovare migrations correnti per ogni model
- [ ] Task 3 — Verificare corrispondenza colonne vs database (batch 1: Xot module)
- [ ] Task 4 — Verificare corrispondenza colonne vs database (batch 2: User module)
- [ ] Task 5 — Verificare corrispondenza colonne vs database (batch 3: Quaeris module)
- [ ] Task 6 — Verificare corrispondenza colonne vs database (batch 4: altri moduli)
- [ ] Task 7 — Rimuovere colonne obsolele
- [ ] Task 8 — Aggiungere colonne mancanti
- [ ] Task 9 — Aggiornare GitHub e documentazione

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- 88 classi Table totali da verificare
- Batch processing per modulo per velocizzare
- Uso di reflection per identificare campi model
- Uso di migration files per schema database

## Testing

Da eseguire:
- `vendor/bin/phpstan analyse` dopo modifiche
- Verifica visuale delle tabelle dopo rimozione/aggiunta colonne
- Test che non ci siano errori runtime

## Dependency Maps

Bloccata da: nessuna
Blocca: miglioramenti UI/UX con schema.org

## Owned File/Module Scope

Tutte le 88 classi Table in tutti i moduli

## Learnings from Previous Stories

- Processing batch per modulo efficiente
- Reflection utile per analisi rapida
- Documentation migrations importante per tracciabilità

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List
- 2026-09-11: Story creata per continuare domani
- 88 classi Table da verificare
- Batch processing per modulo pianificato

### File List

- `laravel/Modules/Xot/docs/stories/xotbaseresourcetable-database-columns-verification.story.md` (nuovo)

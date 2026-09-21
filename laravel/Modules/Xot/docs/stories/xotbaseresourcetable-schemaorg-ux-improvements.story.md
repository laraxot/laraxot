---
title: "XotBaseResourceTable: miglioramenti UI/UX con standard schema.org"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-schemaorg-ux-improvements
status: ready-for-dev
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
status_note: "Story creata per continuare domani il miglioramento UI/UX delle classi Table con standard schema.org. Analisi preliminare ha mostrato che tutte le 88 classi Table hanno gia' la proprieta' $model dichiarata, quindi il focus e' solo su miglioramenti UI/UX."
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/*/app/Filament/Resources/*/Tables/*.php"
related:
  - "./xotbaseresourcetable-add-model-property-and-improve-columns.story.md"
  - "https://schema.org/Person"
  - "https://schema.org/Organization"
  - "https://schema.org/Place"
  - "https://schema.org/Thing"
---

# XotBaseResourceTable: miglioramenti UI/UX con standard schema.org

## Story

Come manutentore di Xot, voglio migliorare l'UI/UX delle tabelle Filament usando standard
schema.org dove applicabile, cosi' che le interfacce siano più semantiche, accessibili
e conformi agli standard web.

## Contesto / Baseline

Analisi preliminare ha trovato 88 classi XotBaseResourceTable, tutte con la proprietà
`$model` già dichiarata. Il focus ora è solo su miglioramenti UI/UX con schema.org.

Standard schema.org rilevanti:
- **Person**: givenName, familyName, email, telephone, knowsLanguage
- **Organization**: name, description, url, address
- **Place**: address, geo, openingHoursSpecification
- **Thing**: name, description, identifier

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. Identificare classi Table che possono beneficiare di schema.org (Person, Organization, Place)
2. Per le classi Person: usare PersonColumn o pattern schema.org per name, email, phone
3. Per le classi Organization: migliorare naming e description con schema.org
4. Per le classi Place: migliorare address/location con schema.org
5. Aggiungere attributi semantiche dove appropriati
6. Migliorare leggibilità e accessibilità
7. Aggiornare issue #115 e discussion #117
8. Documentare pattern schema.org usati

## Esplicitamente fuori scope

- Modifiche strutturali alle tabelle
- Cambiamenti ai model
- Modifiche alle migrations

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [ ] Task 1 — Identificare classi candidate per schema.org (Person, Organization, Place)
- [ ] Task 2 — Analizzare ContactsTable per pattern PersonColumn
- [ ] Task 3 — Applicare miglioramenti Person a classi user-related
- [ ] Task 4 — Applicare miglioramenti Organization a classi business-related
- [ ] Task 5 — Applicare miglioramenti Place a classi location-related
- [ ] Task 6 — Verificare accessibilità e semantica
- [ ] Task 7 — Aggiornare GitHub e documentazione

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: Modules/UI/app/Filament/Tables/Columns/PersonColumn.php] — Colonna Person già esistente con schema.org
- [Source: Modules/Quaeris/app/Filament/Resources/ContactResource/Tables/ContactsTable.php] — Esempio con PersonColumn già usato
- Pattern da seguire: person: givenName, familyName, email, telephone
- PersonColumn implementa già schema.org/Person standard

## Testing

Da eseguire:
- Verifica visuale delle tabelle migliorate
- Test accessibilità con screen reader (se possibile)
- Verifica validazione HTML5

## Dependency Maps

Bloccata da: nessuna
Blocca: miglioramenti UI/UX futuri

## Owned File/Module Scope

Classi Table candidate per schema.org:
- Person: Contacts, Profiles, Users, etc.
- Organization: Customers, Teams, etc.
- Place: Locations, Addresses, etc.

## Learnings from Previous Stories

- PersonColumn esistente implementa già schema.org/Person
- Usare componenti esistenti invece di reinventare
- Focalizzarsi su classi dove schema.org aggiunge valore reale

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: Story creata per continuare domani
- 88 classi Table già hanno $model, focus solo su UI/UX
- PersonColumn esistente come riferimento

### File List

- `laravel/Modules/Xot/docs/stories/xotbaseresourcetable-schemaorg-ux-improvements.story.md` (nuovo)

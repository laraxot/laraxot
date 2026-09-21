---
title: "XotBaseManageRelatedRecords: GetRelatedResourceClassAction fallback fallisce"
type: story
module: Xot
epic: null
story_id: null
slug: xotbasemanagerelatedrecords-getrelatedresourceclassaction-fallback-failure
status: superseded
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
status_note: "CORRETTO 2026-09-11: la 'probabile causa' (ambiguita' fra Modules\\Quaeris\\Filament\\Resources\\ContactResource e Modules\\Notify\\Filament\\Resources\\ContactResource) e' verificata FALSA. GetRelatedResourceClassAction deriva il modulo dal MODEL della relazione (Modules\\Quaeris\\Models\\Contact per SurveyPdf::contacts()), non dal nome della Resource: il guess e' sempre Modules\\Quaeris\\Filament\\Resources\\ContactResource, zero ambiguita' possibile per costruzione (verificato via tinker: Str::between + class_exists + is_subclass_of, tutti concordi). La causa REALE del 'Nessuna Resource correlata risolvibile' era getModelClass() rotto/assente in una revisione intermedia (vedi story canonica). Nessuna azione di sviluppo necessaria su GetRelatedResourceClassAction."
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php"
  - "laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php"
  - "laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php"
related:
  - "../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md"
  - "../stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md"
  - "../architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md"
---

# XotBaseManageRelatedRecords: GetRelatedResourceClassAction fallback fallisce

> **SUPERSEDED (2026-09-11)**: l'ipotesi di ambiguita' fra due `ContactResource`
> (sotto) e' stata verificata FALSA — vedi `status_note` in frontmatter.
> Stato autorevole:
> [xotbasemanagerelatedrecords-convention-over-configuration.story.md](xotbasemanagerelatedrecords-convention-over-configuration.story.md).
> Resta come registro storico dell'ipotesi scartata, non come guida.

## Story

Come manutentore di Xot, voglio che `GetRelatedResourceClassAction` risolva
correttamente la Resource correlata per le pagine `ManageRelatedRecords` che
non hanno `$relatedResource` dichiarato, cosi' che l'errore "Nessuna Resource
correlata risolvibile" non si verifichi in produzione.

## Contesto / Baseline

**Errore attuale**: `Webmozart\Assert\InvalidArgumentException: Nessuna Resource correlata risolvibile per Modules\Quaeris\Filament\Resources\SurveyPdfResource\Pages\ManageContacts.`

**Stack trace**:
- `XotBaseManageRelatedRecords.php:265` → `Assert::notNull()`
- `XotBaseManageRelatedRecords.php:178` → `getRelatedResourceClass()`
- `vendor/filament/tables/src/Concerns/InteractsWithTable.php:47`

**Causa radice analizzata**:

1. `ManageContacts` non ha `$relatedResource` dichiarato
2. `GetRelatedResourceClassAction` fallback per convenzione fallisce
3. Esistono DUE `ContactResource` in moduli diversi (Quaeris e Notify)
4. L'Action NON verifica che il model della Resource corrisponda al model della relazione

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. Identificare PERCHÉ `GetRelatedResourceClassAction` restituisce `null` per `ManageContacts`
2. Verificare se `Modules\Quaeris\Filament\Resources\ContactResource` ha form/table class definite
3. Verificare se `class_exists()`/`is_subclass_of()` falliscono per qualche motivo
4. Proporre e implementare una delle tre opzioni documentate:
   - Opzione 1: Dichiarare `$relatedResource` su tutte le pagine
   - Opzione 2: Migliorare `GetRelatedResourceClassAction` con verifica model
   - Opzione 3: Fallback più chiaro con messaggio diagnostico
5. Aggiornare issue #115 e discussion #117 con i findings
6. Verificare con replay HTTP reale su `ManageContacts` che l'errore sia risolto
7. Aggiornare `XotBaseManageRelatedRecords.php.md` con la soluzione implementata

## Esplicitamente fuori scope

- Rimozione di `HasXotTable`/`HasXotForm` (decisione architetturale separata)
- Migrazione completa a delega nativa (story separata)
- Modifica alle pagine cross-modulo (Notify, Chart) - solo Quaeris in scope

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [ ] Task 1 — Debug `GetRelatedResourceClassAction` per capire perché restituisce null
- [ ] Task 2 — Verificare form/table class di `Modules\Quaeris\Filament\Resources\ContactResource`
- [ ] Task 3 — Implementare Opzione 2 (verifica model in Action) come soluzione preferita
- [ ] Task 4 — Aggiornare issue #115 e discussion #117
- [ ] Task 5 — Verificare replay HTTP su `ManageContacts`
- [ ] Task 6 — Aggiornare documentazione

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php] — Logica fallback per convenzione
- [Source: laravel/Modules/Quaeris/app/Filament/Resources/ContactResource.php] — Resource Quaeris senza form/table class
- [Source: laravel/Modules/Notify/app/Filament/Resources/ContactResource.php] — Resource Notify vuota
- [Source: laravel/Modules/Quaeris/app/Models/Contact.php] — Model Contact Quaeris con proprietà complete
- [Source: laravel/Modules/Notify/app/Models/Contact.php] — Model Contact Notify (polymorphic, struttura diversa)

## Testing

Da eseguire:
- `vendor/bin/phpstan analyse Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`
- Replay HTTP reale su `/quaeris/admin/gaia/survey-pdfs/5/contacts`
- Verifica che le colonne/azioni siano quelle di ContactResource corretta

## Dependency Maps

Bloccata da: `xotbasemanagerelatedrecords-convention-over-configuration.story.md` (decisione architetturale su delega vs trait)

## Owned File/Module Scope

- `laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`
- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php`

## Learnings from Previous Stories

- [Source: xotbasemanagerelatedrecords-convention-over-configuration.story.md] — Implementazione precedente con direzione conservativa, ora in `needs-followup`
- L'utente ha richiesto SOLO documentazione senza implementazione in questa sessione

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: Analisi completa dell'errore senza implementazione (su richiesta utente)
- Documentazione aggiornata in `XotBaseManageRelatedRecords.php.md` sezione "ERRORE CRITICO TROVATO"
- Story BMAD creata per tracciare il fix
- Story padre riaperta come `needs-followup`
- Coordinate GitHub aggiornate in tutti i documenti

### File List

- `laravel/Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md` (aggiornato)
- `laravel/Modules/Xot/docs/stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md` (aggiornato)
- `laravel/Modules/Xot/docs/stories/xotbasemanagerelatedrecords-getrelatedresourceclassaction-fallback-failure.story.md` (nuovo)
- `laravel/Modules/Xot/docs/architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md` (aggiornato)
- `laravel/Modules/Xot/docs/index.md` (aggiornato)

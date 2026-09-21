---
title: "{titolo breve, dice il problema non la soluzione}"
type: story
module: {Modulo}
epic: null
story_id: null
slug: {slug-descrittivo-kebab-case}
status: discussion
cold_gate: null
created: 'YYYY-MM-DD'
updated: 'YYYY-MM-DD'
repository: "https://github.com/laraxot/module_{modulo_lowercase}_fila5.git"
github_issue: "https://github.com/laraxot/module_{modulo_lowercase}_fila5/issues/{numero}"
github_discussion: "https://github.com/laraxot/module_{modulo_lowercase}_fila5/discussions/{numero}"
estimated_effort: "{es. 2-4h}"
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "{path/al/file/toccato.php}"
related:
  - "{path/a/doc/collegato.md}"
---

# {stesso titolo}

## Story

Come {ruolo}, voglio {obiettivo}, cosi' che {beneficio}.

## Contesto / Baseline

Da dove nasce il task, cosa e' stato **verificato contro il codice reale**
prima di scrivere la story (comando eseguito + risultato, non "si presume
che").

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. {AC1 — verificabile meccanicamente dove possibile: comando + output atteso}
2. ...

## Esplicitamente fuori scope

Cosa sembra collegato ma non e' incluso, e perche'.

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [ ] Task 1 — ... (AC: 1)
- [ ] Task 2 — ... (AC: 2)

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: {path}#L{da}-L{a}] — {cosa dice}

## Testing

<!-- LOCKED. -->

Comandi da eseguire, esito atteso. Se e' una story di sola analisi, dirlo
esplicitamente invece di lasciare vuoto.

## Dependency Maps

Cosa questa story blocca/e' bloccata da (oltre a `blocked_by`/`blocks` nel
frontmatter, qui la spiegazione discorsiva del perche').

## Owned File/Module Scope

Ripete `owned_scope` in prosa se serve spiegare confini non ovvi (es. "tocca
solo X, non Y anche se sembra collegato, perche'...").

## Learnings from Previous Stories

Cosa hanno gia' imparato/verificato story precedenti collegate (`supersedes`,
`blocked_by`) che non va riscoperto da zero.

## Dev Agent Record

### Agent Model Used

{nome modello}

### Completion Notes List

- {data}: {cosa fatto}

### File List

- `{path}` ({stato})

---

## Perche' `epic`/`story_id` sono `null` invece di un numero

Verificato contro il registro REALE del monorepo,
`docs/planning-artifacts/epics.md` (root, non dentro `Modules/*/docs/`):
definisce **solo Epic 1-3** (Grafici/Dashboard, Report/Export, Contatti/
Campagne — requisiti di prodotto Quaeris). Le numerazioni tipo `18.x` (Xot,
65+ story) e `7.x` (UI, 272 story) **non corrispondono a nessun epic li'
definito** — sono auto-assegnate leggendo l'ultimo numero in `ls`, non
shardate da un epic reale. Il problema e' gia' tracciato e NON risolto in
[`18.53.numerazione-story-duplicata.story.md`](./18.53.numerazione-story-duplicata.story.md)
(75 numeri duplicati su 467, causati da sessioni concorrenti che leggono la
stessa lista). Assegnare un altro numero a mano (in qualunque modulo)
riprodurrebbe lo stesso problema, non lo eviterebbe.

Finche' non esiste un epic REALE in `docs/planning-artifacts/epics.md` per il
lavoro tecnico/refactor (cosa diversa dai requisiti di prodotto che quel file
oggi documenta), le nuove story restano identificate da `slug` +
`github_issue`/`github_discussion` (univoci per costruzione, assegnati da
GitHub, non da una lista locale). I campi ricchi sopra (`owned_scope`,
`blocked_by`/`blocks`/`supersedes`, sezioni `LOCKED`, Dependency Maps,
Learnings from Previous Stories) restano perche' utili indipendentemente dal
numero — sono stati verificati su una story reale e completa
(`Modules/UI/docs/stories/7.12.phpstan-concurrent-tail-contracts.story.md`).

Se in futuro si decide di estendere `docs/planning-artifacts/epics.md` con
epic tecnici reali, questo template va aggiornato per usarli.

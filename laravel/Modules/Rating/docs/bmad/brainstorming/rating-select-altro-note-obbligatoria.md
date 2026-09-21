---
title: "Brainstorming — Select + Textarea sempre; note required su altro"
type: brainstorming
module: Rating
status: decided
updated: 2026-09-16
qmd: "brainstorming selectIsOther empty key vs ALTRO_KEY string"
related:
  - ../architecture/rating-select-altro-note.md
  - ../architecture/rating-select-altro-implementation-spec.md
  - ../epics/rating-select-altro.md
  - ../stories/5.99-select-altro-note-obbligatoria.story.md
---

# Brainstorming — chiave option e UI

## Domanda

Come aggiungere «altro» al Select dei rating con figli, con nota obbligatoria?

## Opzioni

### A — Textarea solo se «altro» (`visible`)

**Respinta** dall’utente: vuole **sempre** Select + Textarea.

### B — Chiave stringa `'altro'` + dehydrate

Pro: esplicita. Contro: colonna `value` numerica; mapping form↔DB.  
Adottata in bozze 2026-09-15 e **ri-proposta da agenti paralleli** come «conferma utente» —
**non supportata** dal prompt scritto in `docs/prompts/01.md`.

### C — Option `''` + placeholder `null` + `selectIsOther` ✅

Pro: allinea DB (`value` null); distinzione non-scelto vs altro senza sentinella testuale.  
Contro mitigato: disciplina `=== ''` (mai `blank()`) + Pest.

## Decisione

**C raffinato.** Documentata in architecture + implementation-spec + design.

Agenti che riscrivono verso B devono citare un **nuovo** prompt utente verificabile, non
commenti interni circolari.

## Host pivot

Pass-through `value`+`note` in IR (story 5.140). Preferenza: whitelist esplicita o
`$rating['pivot']` intero se lo stato nasce solo dai field del trait.

## D-8

`HasRatingValuesFilter` + note: issue #60 / story 5.141 — non in 5.99.

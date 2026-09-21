---
title: "Epic — Select + Textarea value/note"
type: epic
module: Rating
status: open
updated: 2026-09-16
---

# Epic — Select «altro» + note

## Outcome

Valutatore vede sempre Select e Textarea sui criteri con figli; salva value/note sul pivot;
se sceglie «altro» (option chiave **`''`**, placeholder Select = **`null`**) la note è obbligatoria.

## Stories

| ID | Status | GH |
|----|--------|-----|
| Rating/5.99 | ready-for-dev (docs) | #57 #58 disc #59 |
| Rating/5.141 D-8 filtro | ready-for-dev (docs) | #60 |
| Rating/5.142 review gate | ready-for-dev (docs) | #61 |
| Rating/5.143 lang | ready-for-dev (docs) | #62 |
| IR/5.140 smoke Compila | ready-for-dev (dopo 5.99) | IR #36 |
| IR/5.142 Compila review | ready-for-dev | IR #37 |

## Canon

[architecture/rating-select-altro-note.md](../architecture/rating-select-altro-note.md) ·
[architecture/rating-select-altro-implementation-spec.md](../architecture/rating-select-altro-implementation-spec.md) ·
[design](../../stories/rating-altro-option-conditional-textarea-design.story.md)

**Anti-drift:** bozze che rivendicano `ALTRO_KEY = 'altro'` (stringa) sono superseded dal prompt
utente (`docs/prompts/01.md`) e dai commenti GH più recenti su #57/#58.

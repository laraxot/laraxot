---
title: "UX — sezione commenti FO"
type: ux-design
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, ux, comment, design-comuni, a11y]
qmd: "UX commenti FO Design Comuni auth guest empty state CTA login"
related:
  - ./research.md
  - ./filament-blueprint.md
  - ../../../../../Themes/Sixteen/docs/bmad/blueprints/inbound-fo-blog.md
issues: []
discussions: []
---

# UX — commenti FO

## Principio (da Asso / AFC, adattato)

Dopo la «promessa» del contenuto o della presa in carico, il cittadino deve
avere un modo chiaro di rispondere. Il form non è vanity: è prova di ascolto.

## Layout sezione (dopo body)

1. Heading `h2` «Commenti» (`aria-labelledby`).
2. Lista commenti approvati (empty state testuale se nessuno).
3. **Auth:** form textarea + submit (label visibile / `sr-only` coerente).
4. **Guest:** nessun form; link «Accedi per commentare» verso login localizzato.

## Stati

| Stato | UI |
|---|---|
| Thread vuoto + auth | Empty + form (obbligatorio — mai nascondere sezione) |
| Thread con voci + auth | Lista + form |
| Guest | Lista (o empty) + CTA login |
| Pending post-submit | Feedback «in moderazione» (se auto-approve off) |
| Errore validazione | Messaggio sul campo, focus |

## Copy (tono civico)

- Evitare tono commerciale AFC («prenota formazione»).
- Preferire: «Scrivi un commento», «Accedi per partecipare».

## A11y

- Contrasto AA, focus ring, `label`/`for`, errori annunciati.
- Nessuna dipendenza da sole icone.

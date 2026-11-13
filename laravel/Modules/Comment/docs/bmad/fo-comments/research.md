---
title: "Research — commenti FO vs AFC / AFerrari / Asso"
type: research-report
status: decision-input
module: Comment
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, research, afc, aferrari, asso, comment]
qmd: "research commenti AFC Formazione Alessandro Ferrari Asso della Vendita trust"
related:
  - ./product-brief.md
  - ./ux-design.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/afc-aferrari-asso-pattern-analysis.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/improvement-reasoning.md
issues: []
discussions: []
---

<!--
GateGuard: 1) Does not duplicate Fixcity afc-aferrari-asso-pattern-analysis
(editorial inbound); this file scopes civic COMMENT threads. 2) Linked from
README, product-brief, ux-design, decision-log. 3) New slice for Comment owner.
4) User asked to study the three sites then improve + BMAD docs.
-->

# Research — cosa copiare e cosa no

Fonti (2026-10-16):

- [AFC Formazione blog](https://www.afcformazione.it/blog/)
- [Differenza capo/leader](https://www.afcformazione.it/blog/differenza-tra-capo-e-leader/)
- [Alessandro Ferrari articoli](https://www.aferrari.it/articoli/)
- [Asso della Vendita blog](https://www.assodellavendita.it/blog/)

## Fatto osservato

I tre siti **non** espongono un thread commenti pubblico sul dettaglio. Il
percorso è editoriale: promessa → problema → metodo → strumento → CTA → relazione.

## Adattamento FixCity

| Pattern esterno | Traduzione civica |
|---|---|
| CTA «parti da dove si rompe» | Commento su ticket/evento dove serve il dialogo |
| Fiducia post-promessa (Asso) | Commenti moderati = prova che la PA ascolta |
| Cluster (AFerrari) | Commenti non sostituiscono i pillar |
| Densità operativa (AFC) | Form chiaro, moderazione, empty state utile |

Commenti FixCity = **dialogo moderato**, non forum commerciale né vanity KPI.

## Gap codice (verificato)

1. Blog/eventi: widget nascosto se zero commenti.
2. Ticket: widget sempre montato (modello corretto).
3. Prop FO spesso `writable` invece di `readOnly`.
4. `Http/Livewire` ancora nel tree Comment.
5. Stories CMT-011…015 stale (AC ancora su Livewire).

## Priorità

| P | Azione |
|---|---|
| P0 | Sempre montare `CommentsWidget` + guest CTA |
| P0 | Props canoniche `readOnly` / morph keys |
| P1 | Delete `Http/Livewire` Comment |
| P1 | Blueprint moderazione + FO |
| P2 | A11y Design Comuni + E2E + rate limit |

## Non copiare

Anonimi, reaction vanity, lead scoring dietro al form, secondo motore commenti Blog legacy.

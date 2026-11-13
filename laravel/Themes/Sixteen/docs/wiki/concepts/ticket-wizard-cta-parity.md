---
title: "ticket wizard cta parity"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "ticket wizard cta parity"
issues: []
discussions: []
status: draft
---

# segnalazione wizard cta parity

## Obiettivo

Allineare il wizard `/it/tests/segnalazione-crea` alla reference Design Comuni con CTA primaria unica e lessico coerente.

## Decisione UI

- mantenere `Avanti` come CTA primaria;
- rimuovere dal rendering visivo il footer wizard Filament duplicato (`Successivo`);
- conservare classi semantiche Bootstrap Italia nel markup, con implementazione Tailwind + Alpine + Lit.

## Implementazione

- file owner: `Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`;
- regola CSS locale: `.ticket-wizard-root .fi-sc-wizard-footer { display: none !important; }`;
- build tema obbligatoria: `npm run build` + `npm run copy`.

## Reference

- [segnalazione 01 privacy](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html)

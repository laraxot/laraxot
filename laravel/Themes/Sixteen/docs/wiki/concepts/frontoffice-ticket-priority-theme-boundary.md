---
title: "Frontoffice Ticket Priority Theme Boundary"
type: concept
confidence: high
created: 2026-05-26
updated: 2026-05-26
tags: [sixteen, fixcity, wizard, select, priority, design-comuni]
related:
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/frontoffice-ticket-priority-default-rule.md
---

# Frontoffice Ticket Priority Theme Boundary

Il tema Sixteen non deve correggere con CSS un select di priorita' che non appartiene al wizard pubblico.

## Boundary

- Se il problema visuale riguarda `priority` nello step dati di `segnalazione-crea`, il fix e' nello schema Fixcity: rimuovere il select pubblico e mantenere un default nascosto.
- Il tema resta responsabile dello stile dei controlli realmente presenti: `type`, input testuali, textarea, `coordinate-picker-lit`, upload allegati.
- Non aggiungere regole CSS page-specifiche per nascondere `priority`: il DOM deve essere corretto alla sorgente.

## URL di verifica

`/it/tests/segnalazione-crea?step=form.data%3A%3Adata%3A%3Awizard-step`

---
title: Context compression and summary Infolist rule
type: concept
updated: 2026-04-22
tags: [sixteen, design-comuni, wizard, infolist, context-compression]
sources:
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/context-compression-and-summary-infolist-rule.md
  - ../../../../../../docs/wiki/concepts/openrouter-context-compression-plugin.md
---

# Context compression and summary Infolist rule

## Tema Sixteen

Il tema Sixteen puo' governare layout, spacing e Design Comuni parity del wizard segnalazione, ma non deve reintrodurre `SchemaView` o Blade custom per rimappare lo stato del riepilogo.

Il mapping del summary resta nel widget Fixcity tramite entry Infolist Filament 5 dentro `getSummarySchema()`.

## Regola operativa

- Visual parity: tema.
- Dati read-only strutturati: Infolist nel widget.
- Memoria e contesto: LLM Wiki + QMD, non output massivi nel prompt.
- OpenRouter `context-compression`: plugin API lato provider, non pacchetto del tema.

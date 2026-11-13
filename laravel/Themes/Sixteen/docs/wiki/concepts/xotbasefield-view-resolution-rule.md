---
title: "xotbasefield view resolution rule"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "xotbasefield view resolution rule"
issues: []
discussions: []
status: draft
---

# XotBaseField View Resolution Rule (Theme)

## Regola

Quando un field Filament estende `XotBaseField`, la view non va forzata nel field con `$view`: e' calcolata a runtime.

## Impatto per il tema

- Il tema non deve "compensare" field non conformi con override strani.
- Se un field non renderizza, si risolve il mapping view nel resolver (Xot), non nel Blade del tema.

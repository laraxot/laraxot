---
title: "Il tema rispetta il confine Filament XotBase"
type: concept
tags: [theme, filament, xotbase, boundary]
created: 2026-07-16
updated: 2026-07-16
qmd: "theme Filament XotBase boundary never direct extend"
issues:
  - https://github.com/laraxot/base_techplanner_fila5/issues/45
discussions:
  - https://github.com/laraxot/base_techplanner_fila5/discussions/43
related:
  - ../../../../../../docs/wiki/rules/filament-xotbase-same-path.md
---

# Il tema rispetta il confine Filament XotBase

Anche un tema è un consumatore dell'architettura Laraxot: se introduce classi
Filament custom, eredita una base `Modules\Xot\Filament\*`, mai Filament
direttamente. Il tema decide la presentazione; Xot assorbe il contratto e gli
upgrade del framework. Questo mantiene la direzione `Xot → UI → moduli → temi`
senza duplicare adattatori nel layer visuale.


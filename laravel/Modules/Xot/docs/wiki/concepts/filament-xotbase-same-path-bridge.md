---
title: "XotBase same-path bridge per Filament"
type: concept
tags: [xot, filament, xotbase, boundary]
created: 2026-07-16
updated: 2026-07-16
qmd: "XotBase same path bridge Filament abstract boundary"
issues:
  - https://github.com/laraxot/base_techplanner_fila5/issues/45
discussions:
  - https://github.com/laraxot/base_techplanner_fila5/discussions/43
related:
  - ../../../../../../docs/wiki/rules/filament-xotbase-same-path.md
---

# XotBase same-path bridge per Filament

Il modulo Xot possiede il confine con Filament. Ogni famiglia estensibile usata
dai moduli espone qui una base astratta nello stesso percorso concettuale:
`Tables/Columns`, `Forms/Components`, `Resources/Pages`, `Actions`, `Widgets`.

La base è intenzionalmente sottile: eredita Filament e contiene solo policy
trasversali reali. Non replica API, non anticipa bisogni e non diventa un Service.
Le classi di dominio ereditano la base più specifica disponibile.


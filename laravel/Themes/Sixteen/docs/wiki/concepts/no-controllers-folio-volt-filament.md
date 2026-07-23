---
title: "No Controllers — Solo Folio + Volt + Filament"
type: concept
confidence: high
created: 2026-05-29
tags: [sixteen, architecture, controllers, folio, volt]
related:
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/no-controllers-folio-volt-filament.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/fixcity-best-practices.md
  - ../../../../../docs/wiki/concepts/stack-folio-volt-filament.md
---

# No Controllers — Solo Folio + Volt + Filament

## Regola Permanente

**NON creare `app/Http/Controllers/` in questo tema.**

Sixteen, come tutti i moduli del progetto, usa solo **Folio + Volt + Filament + Actions**.

Eventuali controller esistenti in Sixteen (`CieAuthController`, `SpidAuthController`, `LogoutController`, `BaseController`, `ComuneController`) sono legacy da migrare. Non crearne di nuovi.

## Riferimenti

- Vedi [Fixcity no-controllers](../../../../../Modules/Fixcity/docs/wiki/concepts/no-controllers-folio-volt-filament.md) per la regola completa

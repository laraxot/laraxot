---
title: "UI — Filament Blade canonical (modulo UI)"
type: concept
status: active
created: 2026-05-28
tags: [filament, ui, blade]
related:
  - "./auth-register-focus-loss-overlay.md"
  - "./block-rendering-and-optional-services.md"
  - "./claude-audit-static.md"
  - "./code-redundancy-ui.md"
  - "./context-overflow-prevention.md"
  - "./enum-select-best-practices.md"
  - "./enum-select-component.md"
  - "./enum-select-contract-and-false-friends.md"
---

# UI — Filament Blade canonical

Il modulo **UI** documenta l’uso dei componenti Blade Filament condivisi tra temi e moduli.

## Regola

Leggere prima [filament-first-rule.md](../../../../../../docs/wiki/rules/filament-first-rule.md) (Rule 019). Il file [filament-components.md](../../blade/filament-components.md) elenca mapping tag → anti-pattern.

## Filament 5

- Overview: https://filamentphp.com/docs/5.x/components/overview
- Tabs: https://filamentphp.com/docs/5.x/components/tabs

## Non duplicare

I file in `Themes/docs/shared-components/` restano storici CMS; per nuovo lavoro usare questa catena: **wiki root → UI blade → tema Sixteen**.

---
title: "Sixteen — no Livewire standalone in frontoffice"
type: concept
status: active
created: 2026-05-28
related:
  - ../../../../../../docs/wiki/concepts/no-pure-livewire-outside-filament-widgets.md
  - ./ticket-list-map-integration.md
---

# Sixteen — no Livewire standalone

Regola progetto: [no-pure-livewire-outside-filament-widgets.md](../../../../../../docs/wiki/concepts/no-pure-livewire-outside-filament-widgets.md).

- `/it`: `pages/index.blade.php` → `<x-page slug="home" />` (blocco CMS `segnalazioni-layout`).
- Wizard: view sotto `filament/widgets/`, non `app/Livewire/`.
- Parity elenco: [STORY-058](../../../../../../docs/stories/STORY-058-it-ticket-list-html-visual-parity.md).

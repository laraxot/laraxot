---
title: "Product brief — widget-only nel panel"
type: product-brief
module: Xot
status: approved
related:
  - ./livewire-widget-prd.md
  - ./livewire-inventory.md
  - ./livewire-widget-advantages.md
---

# Brief: il panel parla solo Filament widget

Chi sviluppa un modulo Laraxot non deve scegliere tra `Http\Livewire` e widget per il chrome admin. Il guscio è `XotBaseWidget`. HTTP resta per FO che non è panel (Cms page, Media clip) finché non ha un gemello widget/Page.

Metrica: grep `@livewire('alias')` nei panel provider = 0.

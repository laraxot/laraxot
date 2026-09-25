---
title: "Architecture — XotBaseWidget vs XotBaseComponent"
type: architecture
module: Xot
status: approved
related:
  - ./livewire-widget-prd.md
  - ./livewire-inventory.md
  - ./livewire-widget-advantages.md
---

# Architecture

```
XotBasePanelProvider.discoverWidgets(Filament/Widgets)
        │
        ├─ KPI          → $isDiscovered true
        └─ chrome hook  → $isDiscovered false + FQCN nel provider foglia

Http/Livewire/XotBaseComponent  → solo eredità storica FO, non nuovi figli admin
```

### ADR-X001

Widget = Livewire specializzato. Non si “toglie Livewire”.

### ADR-X002

Alias `@livewire('foo.bar')` nel panel è un difetto di tipo. FQCN.

### ADR-X003

`manage_lang_module` senza classe in `Http/Livewire` è debito di vista: story Xot 12.1 grep + ritiro o page Lang.

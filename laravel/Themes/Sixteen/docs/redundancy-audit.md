---
title: "Sixteen redundancy audit 2026-05-21"
type: audit
theme: Sixteen
tags: [redundancy, blade, components, design-system]
created: 2026-05-21
related:
  - https://github.com/laraxot/base_fixcity_fila5/issues/89
---

# Sixteen redundancy audit 2026-05-21

High-risk findings:
- Many components are duplicated between root `components/` and grouped folders: buttons, form inputs, dropdowns, modal, section, banner, welcome, action-section, and section-title.
- Bootstrap Italia aliases duplicate grouped components: `header-main`, `header-slim`, `breadcrumb`, `card`, `alert`.
- UI primitives overlap with `Themes/TwentyOne`: `button`, `input`, `checkbox`, `badge`, `modal`, `logo`, `text-link`, `light-dark-switch`, `placeholder`.
- Page route file `[container0]/[slug0]/index.blade.php` is byte-identical with TwentyOne.

Risk:
- Component aliases are useful during migration, but without a canonical owner they multiply maintenance.
- Theme-to-theme copies hide whether Sixteen is a real design-system owner or only a fork of TwentyOne.

Suggested cleanup order:
1. Build a component alias map: canonical component, legacy alias, remove-after date.
2. Keep Design Comuni / Bootstrap Italia names only where they represent semantic public API.
3. Move cross-theme primitives to a shared module/theme base only after checking all Blade includes.

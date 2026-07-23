---
title: "Header partials location rule"
type: concept
confidence: high
updated: 2026-04-21
tags: [header, partials, ownership, dry, kiss, blade]
sources:
  - ../../../../resources/views/components/sections/header/v1.blade.php
  - ../../../../../../docs/wiki/concepts/sixteen-header-composition-rule.md
  - ../../../../../../_bmad-output/implementation-artifacts/8-37-blade-reusable-components-extraction-and-header-partials-governance.md
---

# Header partials location rule

## Regola

Quando un blocco Blade e' legato all'owner `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`, il partial deve stare in:

- `laravel/Themes/Sixteen/resources/views/components/sections/header/partials/`

e non al livello root di `sections/header/`.

## Perche'

- preserva la distinzione tra **entrypoint owner** (`v1.blade.php`) e **pezzi interni**;
- riduce collisioni tra versioni/entrypoint header;
- mantiene DRY + KISS: ownership chiara, riuso esplicito, zero duplicazioni confuse.

## Regola estesa

La componentizzazione va cercata su tutte le Blade del tema:

- se il blocco e' locale all'header owner, usare `header/partials/`;
- se il blocco e' cross-page/cross-section reale, usare un componente riusabile nella tassonomia generale.

## Collegamenti

- [header composition rule (root)](../../../../../../docs/wiki/concepts/sixteen-header-composition-rule.md)
- [header section owner rule (root)](../../../../../../docs/wiki/concepts/header-section-owner-rule.md)
- [header authenticated state](./header-authenticated-state.md)

---
title: "PHPStan Modules — zero errori (2026-07-24)"
type: concept
module: Media
tags: [phpstan, tests, pascalcase]
created: 2026-07-24
updated: 2026-07-24
related:
  - ../../../../../../docs/chat/handoff-phpstan-modules.md
  - ../../../../../../docs/wiki/memories/psr4-test-filename-pascalcase.md
---

# Media — PHPStan e cartelle test

Rimossa `tests/unit/` (lowercase): duplicava `tests/Unit/` e PHPStan segnalava 14 errori sul file stale `SaveAttachmentsActionTest` (firma `execute` con DTO vs array).

Canon: solo `tests/Unit/` e `tests/Feature/` (PascalCase). Restano da pulire eventuali `tests/feature` / `tests/filament` lowercase se presenti.

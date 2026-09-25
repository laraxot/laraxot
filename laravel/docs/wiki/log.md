---
title: "TechPlanner LLM Wiki Log"
type: "log"
tags:
  - second-brain
  - qmd
  - techplanner
created: "2026-06-06"
updated: "2026-06-06"
qmd: "tp-wiki-root"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/11"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/12"
---

# TechPlanner LLM Wiki Log

## 2026-06-06

- Added `public-theme-resolution-and-vite-assets`: prevent wrong `pub_theme` diagnosis and missing Vite deploy assets for Theme Two.
- Expanded prevention checklist (tenant matrix, regression signals, build guard) after `/it` Sixteen vs Two incident on master.
- Linked Tenant module doc `tenant-name-to-pub-theme.md` with SSoT TechPlanner = Two and anti-patterns for AI agents.
- Push master: allineato con `origin/master`; `pub_theme=Two` verificato runtime; `/it` serve solo asset `themes/Two/`.
=== COMPLETED PHPSTAN FIXES ===
2026-09-25
Errors fixed:
1. Comment/PendingCommentNotification mailView() return type: Changed string to view-string
2. Comment/ApprovedCommentNotification mailView() return type: Changed string to view-string
3. UI/Render/Block view property type assertion
=== RUN BMAD workflow completed ===

---

## [2026-09-25] phpstan | Full Modules sweep — 0 errors
- `cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=8G` → `[OK] No errors` EXIT 0.
- Errors fixed this session:
  1. Activity/CanPaginate template covariance (6 errors)
  2. Comment/Notification mailView view-string (4 errors)
  3. Job/TaskBusinessLogicTest staticMethod.alreadyNarrowedType (1 error)
  4. Rating/HasRationsTrait staticMethod.notFound (2 errors)
  5. UI/OpeningHoursField deprecated Placeholder → TextEntry (2 errors)
  6. UI/lang/it array_merge mixed → array annotation (15 errors)
  7. UI/Render/Block view property assign.propertyType (1 error)
  8. Xot/XotForkedInvoke is_int alreadyNarrowedType (1 error)
- UI module interactive rebase (29 conflicted files) resolved forward-only.
- All modules verified: `git -C <path> rev-parse --show-toplevel` matches, no rebase in progress.

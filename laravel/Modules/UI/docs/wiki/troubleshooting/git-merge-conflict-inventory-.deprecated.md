---
title: "Git Conflict Inventory"
type: concept
tags: [git, merge, conflict, inventory]
created: 2026-07-14
updated: 2026-07-14
qmd: "git-merge-conflict-inventory-2026-04-28.deprecated git conflict inventory"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./git-merge-conflict-inventory-1.md"
  - "./git-merge-conflict-inventory.md"
  - "./git-push-lfs-missing-objects.md"
  - "./module-theme-root-hygiene.md"
  - "./phpstan-fixes-1.md"
  - "./phpstan-fixes.md"
---

# Git Conflict Inventory

- Date: 2026-04-28
- Owner: Modules/UI
- Files with conflict markers: 2

## Files

- docs/strategy.md
- docs/studio-card-selector-implementation-2.md

## Notes

- Inventory generated from `rg -l "^(<<<<<<<|=======|>>>>>>>)"`.
- Use this list as a volatile coordination map; re-open each file before editing because other agents may resolve items in parallel.
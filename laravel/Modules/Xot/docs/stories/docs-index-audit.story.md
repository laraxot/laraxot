---
title: "Docs index audit — Xot"
type: story
module: Xot
story_id: docs-index-audit
slug: docs-index-audit
status: done
created: 2026-09-03
updated: 2026-09-03
repository: "git@github.com:laraxot/module_xot_fila5.git"
---

# Docs index audit — Xot

## Story

As a maintainer, I want `docs/index.md` to reflect the real size and shape of `Modules/Xot/docs/`
(8383 `.md` files, not the ~2437 loose root files initially assumed), organized by topic, so that
the documentation is navigable without moving or deleting any existing file.

## What was done

- Audited the full `docs/` tree (98 top-level dirs, 8383 `.md` files).
- Rebuilt `docs/index.md`: per-topic sections (subfolder-based topics merged with keyword-classified
  loose root files), a "Da classificare" bucket for unmatched loose files, and a "Storico / da
  consolidare" section listing dump/mirror dirs (`archive/`, `consolidated/`, `historical/`, `raw/`,
  `root-md-files/`, `root-txt-files/`, `-integration/`, `_integration/`, `old_tasks/`) by count only.
- Verified all 3312 generated links resolve to real files; zero files renamed, moved, or deleted.
- Flagged competing legacy index attempts (`00-index.md`, `00-INDEX.md`, `01-index-details.md`, etc.)
  as superseded-but-kept.

## Scope note

Docs-only change (`docs/` under `Modules/Xot`). No `.php` files touched — a parallel session has
in-flight, uncommitted work on `app/Filament/Traits/HasXotTable.php` plus pre-existing conflict
markers elsewhere in the module.

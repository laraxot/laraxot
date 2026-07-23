---
title: "Comment redundancy audit 2026-05-21"
type: audit
module: Comment
tags: [redundancy, duplicate-code, docs]
created: 2026-05-21
related:
  - https://github.com/laraxot/base_fixcity_fila5/issues/89
---

# Comment redundancy audit 2026-05-21

Static metrics: 329 files scanned, 2 case-only groups, 4 duplicate hash groups, 0 duplicate FQCN.

Findings:
- Case-only docs: `INDEX.md`/`index.md`, `PRD.md`/`prd.md`.
- `routes/web.php` and `routes/api.php` are byte-identical empty route files.
- Vendored Spatie packages contain expected duplicated boilerplate such as licenses and PHP-CS config.
- `resources/assets/js/app.js` is byte-identical to a vendored empty Blade/action stub; likely low-risk boilerplate.

Risk:
- Main risk is docs casing; package duplication inside vendored code should not be cleaned without upstream-aware work.

Suggested cleanup order:
1. Normalize docs casing only.
2. Leave package boilerplate untouched unless the vendored packages are being removed or updated.
3. If empty API/web route files remain, document why both are intentionally present.

Evidence commands:
- Per-owner static scan for case-only paths, byte-identical files, and duplicate FQCN.
- GitHub tracker: issue #89.

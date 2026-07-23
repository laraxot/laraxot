---
title: "lang split UI — claude-audit large file"
type: memory
module: UI
tags: [ui, i18n, claude-audit, lang-split]
created: 2026-07-09
updated: 2026-07-09
qmd: "UI icon_state_group opening_hours_field split claude-audit"
issues:
discussions:
related:
---

# Split lang UI (claude-audit)

File `icon_state_group.php` e `opening_hours_field.php` → loader + `{name}_{section}.php` per chiave top-level (`fields`, `navigation`, …).

Script: `bashscripts/tools/split-module-lang-monolith-for-audit.php UI it <fileBase>`.

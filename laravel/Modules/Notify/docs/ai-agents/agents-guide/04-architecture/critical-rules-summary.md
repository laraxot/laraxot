---
title: "Critical Rules Summary"
type: rule
tags: [critical, rules, summary]
created: 2026-07-14
updated: 2026-07-14
qmd: "critical-rules-summary critical rules summary"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./critical-architecture-rules.md"
---

# Critical Rules Summary

1. **PHPStan Level 10** — all errors must be fixed (no ignores).
2. **`declare(strict_types=1)`** in every file.
3. **Short array syntax `[]`** ALWAYS, NEVER `array()`.
4. **`property_exists()` forbidden** on Eloquent models — use `isset()`.
5. **`Log::debug()` forbidden** — use `Log::info/warning/error`.
6. **No constructor DI in Actions** — use `app(ActionClass::class)->execute()`.
7. **No direct Filament extensions** — use `XotBase*` wrappers.
8. **No hardcoded labels** — use translation keys.
9. **String keys required** in `getTableColumns()`, `getFormSchema()`, `getHeaderActions()`.
10. **`form()` and `table()` are `final`** — override `getFormSchema()` / `getTableColumns()`.
11. **Model classes MUST be singular** (e.g. `Scheda`, not `Schede`); table names stay plural.
12. **One workspace file per module** — named `_<module_name_in_snake_case>.code-workspace` (e.g., `_xot.code-workspace`, `_activity.code-workspace`).
13. **Source code in `app/` only** — Data Objects, Actions, Models, Services MUST be in `app/`, NEVER in module root.

---
[Back to index](../index.md) | [Full Rules](../critical-rules.md)

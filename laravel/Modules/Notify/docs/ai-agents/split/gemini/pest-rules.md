---
title: "Pest Rules"
type: rule
tags: [pest, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "pest-rules pest rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./boost-integration.md"
  - "./boost-rules.md"
  - "./filament-rules.md"
  - "./fluxui-rules.md"
  - "./folio-rules.md"
  - "./foundation-rules.md"
  - "./laravel-core-rules.md"
  - "./laravel-v12-rules.md"
---

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.


---

## Cross-References

- ← [GEMINI Index](INDEX.md) — All Gemini guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
- ← [../../../../laravel/gemini.md](../../../../laravel/../../../../laravel/gemini.md) — Original source


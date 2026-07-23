---
title: "6. Filament (Admin) Patterns"
type: pattern
tags: [filament, admin, patterns]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-admin-patterns 6. filament (admin) patterns"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
---

# 6. Filament (Admin) Patterns

- ALWAYS extend XotBase classes (NOT raw Filament classes)
- Use AutoLabelAction (NEVER use `->label()`)
- Translation keys: `module::resource.field.attribute`
- NEVER hardcode labels - use auto-generated translations

| Filament Class | Use Instead |
|----------------|-------------|
| `Resource` | `XotBaseResource` |
| `Page` | `XotBasePage` |
| `Widget` | `XotBaseWidget` |

---


---
title: "Model And Docs Naming Governance"
type: concept
tags: [model, docs, naming, governance]
created: 2026-07-14
updated: 2026-07-14
qmd: "model-and-docs-naming-governance model and docs naming governance"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./file-patterns-and-docs-standards.md"
  - "./modules-themes-docs-audit.md"
---

# Model And Docs Naming Governance

## Scope

This governance applies to:

- `laravel/Modules/*/app/Models`
- `laravel/Modules/*/docs`
- `laravel/Themes/*/docs`

## Rules

1. Model class names must be singular (`Scheda`, `Progressione`, `Valutatore`).
2. Database table names stay plural (`schede`, `progressioni`, `valutatori`).
3. Filament resource `$model` must reference the singular model class.
4. Factories must use singular model names (`SchedaFactory` for `Scheda`).
5. Any model rename must include code + docs + tracking update in the same change.

## Docs Hygiene

1. Keep one canonical filename per topic (`kebab-case`).
2. Do not create both `my-file.md` and `my_file.md` for the same topic.
3. Put historical duplicates under an explicit archive folder and link the canonical file.
4. Add or update one `README.md` or `00-index.md` in each `docs/` folder.
5. Prefer relative links; avoid stale absolute references.

## Required Checklist For Model Renames

- [ ] Model filename and class updated
- [ ] Factory filename/class updated
- [ ] Filament resource references updated
- [ ] Policy type hints updated
- [ ] PHPDoc generics/type hints updated
- [ ] Module docs updated
- [ ] Theme docs/indexes updated if they reference the model
- [ ] GitHub issue/discussion updated

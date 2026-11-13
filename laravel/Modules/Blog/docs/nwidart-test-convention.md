---
title: "Nwidart Test Convention — Blog"
type: note
status: draft
updated: 2026-10-19
tags: [docs, blog]
qmd: "nwidart test convention   blog blog"
---
# Nwidart Test Convention — Blog

- Tests: `laravel/Modules/Blog/tests/`.
- Fixtures owned by the module: `laravel/Modules/Blog/tests/Fixtures/`.
- Application, cross-module and theme tests may live under `laravel/tests/`, but
  they are not substitutes for Blog's module suite.
- Never create a second `laravel/tests/Modules/Blog/` tree.

---
title: "Theme Zero Docs Governance"
type: guide
tags: ['theme', 'model-docs-governance']
created: 2026-07-14
updated: 2026-07-14
qmd: "theme zero docs governance"
related:
  - "./00-index.md"
id: zero-docs-bmad-model-docs-governance
slug: model-docs-governance
description: "Regole di governance della docs: nomi di modello al singolare, indici accurati tra moduli e temi."
document_type: guide
category: architecture
status: active
issues: []
discussions: []
---

# Theme Zero Docs Governance

## Objectives

1. Avoid ambiguous references between singular model names and plural table names.
2. Keep documentation indexes accurate across modules and themes.

## Rules

1. Model names in docs must be singular (`Scheda`, `User`, `Valutatore`).
2. Table names in docs remain plural (`schede`, `users`, `valutatori`).
3. Prefer canonical `kebab-case` docs filenames.
4. When introducing a new docs topic, add a link in `00-index.md` or `README.md`.

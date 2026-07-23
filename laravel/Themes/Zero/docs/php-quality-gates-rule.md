---
title: "Theme Zero - PHP Quality Gates Rule"
type: rule
tags: ['testing', 'phpstan']
created: 2026-07-14
updated: 2026-07-14
qmd: "theme zero - php quality gates rule"
related:
  - "./00-INDEX.md"
  - "./00-index.md"
---

# Theme Zero - PHP Quality Gates Rule

## Regola tema
- Ogni modifica PHP nel tema deve passare:
  - `phpstan`
  - `phpmd`
  - `phpinsights`

## Test
- Quando testabile, coprire con test Pest o aggiornare test esistenti.

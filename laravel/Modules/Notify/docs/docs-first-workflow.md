---
title: "Docs-First Workflow (Notify)"
type: concept
tags: [docs, first, workflow]
created: 2026-07-14
updated: 2026-07-14
qmd: "docs-first-workflow docs-first workflow (notify)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Docs-First Workflow (Notify)

## Regola locale

Prima di ogni modifica a codice/test del modulo Notify:

1. studiare la documentazione locale (`00-index.md` + documenti dell'area impattata);
2. aggiornare/migliorare almeno un documento in `Modules/Notify/docs`;
3. allineare regole globali in `docs/rules`, `docs/memory`, `docs/skills` se la modifica tocca il workflow;
4. valutare aggiornamento/creazione di GitHub Issue e GitHub Discussion;
5. solo dopo procedere con patch di codice.

## Nota PSR-4 per i test

Nei test Notify evitare classi helper nominate dentro file con nome diverso.

- Preferire classi anonime o fixture dedicate.
- Obiettivo: zero warning Composer su `does not comply with psr-4 autoloading standard`.

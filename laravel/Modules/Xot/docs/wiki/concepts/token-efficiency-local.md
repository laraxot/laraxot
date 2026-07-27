---
title: "Token efficiency — disciplina locale Xot"
type: concept
module: Xot
tags: [tokens, qmd, xot, framework, context-mode]
created: 2026-07-24
updated: 2026-07-24
qmd: "xot token efficiency framework qmd query no domain actions context-mode"
related:
  - ./context-mode-xot-discipline.md
  - ./no-domain-actions-in-xot.md
  - ../../../../../../docs/wiki/concepts/token-efficiency-2026.md
  - ../../../../../../docs/wiki/rules/token-optimization-discipline.md
---

# Token efficiency — modulo Xot

## Perché

Xot è **framework**: wiki densa. Caricare tutto il tree Xot brucia il budget. La disciplina locale = file atomici ≤200 righe + retrieval QMD.

## Pratica owner

| Fare | Non fare |
|------|----------|
| `llm-wiki-qmd.sh query` + Read mirato | Preload `Modules/Xot/docs/` |
| Context-mode discipline locale | Actions di dominio AI/Geo in Xot |
| Split note >200 righe | Dump PHPStan intero in chat |

Vedi [context-mode-xot-discipline.md](./context-mode-xot-discipline.md) · canon globale [token-efficiency-2026](../../../../../../docs/wiki/concepts/token-efficiency-2026.md).

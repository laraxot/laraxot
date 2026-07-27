---
title: "Token efficiency agente — tema Sixteen"
type: concept
module: Sixteen
tags: [tokens, qmd, theme, sixteen, second-brain, agent]
created: 2026-07-24
updated: 2026-07-24
qmd: "sixteen theme agent token efficiency qmd query second brain not css fo-pa"
related:
  - ./composer-go-theme-impact.md
  - ./bmad-context-compression.md
  - ../../../../../../docs/wiki/concepts/token-efficiency-2026.md
  - ../../../../../../docs/wiki/rules/token-optimization-discipline.md
---

# Token efficiency agente — Sixteen

> **Non** confondere con token CSS/FO-PA (`fo-pa-tokens-uniformity.md`): qui = **token LLM/context**.

## Perché

Docs tema Sixteen sono molte (AGID, parity, CSS). L’agente deve retrieval-on-demand, non preload cartella `docs/`.

## Pratica

```bash
bashscripts/docs/llm-wiki-qmd.sh query "sixteen design comuni header"
```

- Max 5 file owner dopo QMD
- CSS grandi → Grep + Read offset/limit
- Write-back decisioni tema in `Themes/Sixteen/docs/wiki/`

Canon: [token-efficiency-2026](../../../../../../docs/wiki/concepts/token-efficiency-2026.md).

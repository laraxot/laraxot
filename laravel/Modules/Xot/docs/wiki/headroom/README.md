---
title: "Headroom - Modulo Xot"
type: how-to
tags: [headroom, module, xot, codex, mcp]
module: Xot
created: 2026-08-02
updated: 2026-08-02
qmd: "headroom modulo Xot proxy codex mcp context compression"
issues:
  - "https://github.com/provtv/base_ptv_fila5/issues/218"
discussions:
  - "https://github.com/provtv/base_ptv_fila5/discussions/219"
related:
  - "../../../../../docs/HEADROOM-INTEGRATION.md"
---

# Headroom - Modulo Xot

Usare la configurazione Headroom comune del progetto quando si lavora su Xot.

## Comandi

```bash
headroom mcp status
headroom doctor
headroom savings
headroom perf --hours 24
```

## Regole

- Config canonica: `../../../../../.headroom.yaml`.
- Guida root: `../../../../../docs/HEADROOM-INTEGRATION.md`.
- Non documentare comandi per-modulo non presenti nel CLI Headroom.
- Coordinare modifiche su issue #218 e discussion #219.

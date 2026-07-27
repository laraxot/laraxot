---
title: "Niente Actions di dominio in Xot"
type: concept
module: Xot
tags: [xot, module-boundaries, actions, ai, geo]
created: 2026-07-24
updated: 2026-07-24
qmd: "Xot no domain actions AI Geo framework base ownership module boundary"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/28"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/29"
related:
  - ../../no-domain-logic-in-xot.md
  - ./second-brain-local-discipline.md
  - ../../../../../docs/wiki/rules/domain-actions-belong-to-domain-module.md
  - ../../../../../docs/wiki/concepts/xot-is-framework-base-not-domain-owner.md
  - ../../../../../docs/wiki/skills/xot-is-framework-base.md
  - ../../../../AI/docs/wiki/concepts/ollama-actions-ownership.md
---

# Niente Actions di dominio in Xot

## Regola locale

`app/Actions/` in Xot = **concern tecnici** (`Cast/`, `File/`, `Array/`, …).
Mai sottocartelle col nome di un modulo esistente (`AI/`, `Geo/`, …).

Detect:

```bash
for dir in app/Actions/*/; do
  name=$(basename "$dir")
  [ -d "../$name" ] && echo "VIOLATION: Xot/Actions/$name"
done
```

## Casi chiusi 2026-07-24

| Era in Xot | Ora |
|------------|-----|
| `Actions/AI/Ollama/*` | `Modules/AI/app/Actions/Ollama/` |
| `Actions/ContextCompressorAction` | `Modules/AI/app/Actions/` |
| `Actions/Geo/GetDistanceExpressionAction` | già in Geo — rimossi duplicati Xot |

Narrativa estesa: [no-domain-logic-in-xot.md](../../no-domain-logic-in-xot.md).
Skill: `xot-is-framework-base`.

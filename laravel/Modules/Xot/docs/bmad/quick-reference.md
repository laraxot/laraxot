---
title: "Xot — BMAD Quick Reference"
description: "Comandi rapidi BMAD per il modulo Xot"
module: "Xot"
alias: "xot"
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
---

# Xot — BMAD Quick Reference

## Comandi Rapidi

### Help
```bash
bmad-help
```

### Agenti per Xot

| Agente | Skill | Scopo |
|--------|-------|-------|
| Mary (analyst) | `skill: "bmad-agent-analyst"` | requisiti, analisi |
| John (pm) | `skill: "bmad-agent-pm"` | prd, user stories |
| Winston (architect) | `skill: "bmad-agent-architect"` | architettura |
| Amelia (dev) | `skill: "bmad-agent-dev"` | implementazione |
| Quinn (qa) | `skill: "bmad-agent-qa"` | test |

## Workflow Tipico per Xot

1. `skill: "bmad-product-brief"`
2. `skill: "bmad-create-prd"`
3. `skill: "bmad-create-architecture"`
4. `skill: "bmad-create-epics-and-stories"`
5. `skill: "bmad-sprint-planning"`
6. `skill: "bmad-dev-story"`

## Review

```bash
skill: "bmad-code-review"
skill: "bmad-review-edge-case-hunter"
```

## Quick Flow

```bash
bmad-quick-dev "Aggiungi XotBaseModel"
bmad-quick-spec "Specifica tecnica XotBaseResource"
```

## Cartelle Chiave

- `_bmad/` — moduli, agenti, skill, config
- `_bmad-output/` — artefatti generati
- `docs/bmad/` — questa documentazione

## Vedi Anche

- [setup-guide](setup-guide.md)
- [merge proposals](MERGE_PROPOSAL_AI_VS_AIASSISTANT.md)

---

*Xot · BMAD Quick Reference · data 2026-05-27*
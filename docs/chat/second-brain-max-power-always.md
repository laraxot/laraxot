---
title: second brain max power always
type: chat
tags: [second-brain, qmd, alwaysApply, agent]
created: 2026-07-27
updated: 2026-07-27
---

# Second brain max power — always on

## Perché

Sessione 2026-07-27: fix reattivi (PHPStan, hygiene) senza healthcheck/QMD = second brain sotto-potenza.

## Cosa è stato messo in produzione

| Artefatto | Ruolo |
|-----------|--------|
| `.cursor/rules/second-brain-max-power.mdc` | **alwaysApply** — checklist 6 passi |
| `agent-bootstrap-on-demand.mdc` | punta a max power |
| `docs/wiki/concepts/agent-bootstrap-compact.md` | workflow 7 passi |
| `docs/wiki/skills/cursor-second-brain-max-workflow.md` | playbook |
| `docs/wiki/memories/second-brain-max-power-discipline.md` | memoria |
| TRIGGER_MAP riga BOOTSTRAP | healthcheck+QMD prima di codice |

## Ordine obbligatorio ogni task

1. healthcheck → 2. TRIGGER 1 riga → 3. QMD -n 5 → 4. wiki owner → 5. chat/claude-mem se multi → 6. write-back docs modulo/tema

Issue: https://github.com/laraxot/base_workorder_fila5/issues/14

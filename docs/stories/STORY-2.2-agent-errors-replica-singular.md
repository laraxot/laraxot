---
title: "Story 2.2 — Errori agente + guardrail replica singolare"
type: story
tags: [bmad, story, agent-errors, replica, singular, gestionale-commesse]
created: 2026-07-23
updated: 2026-07-23
status: ready-for-dev
qmd: "story 2.2 agent errors never repeat replica singular AiAssistant"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/10"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../../../../gestionale-commesse-comparison/agent-errors-lessons.md"
  - "../../../../gestionale-commesse-comparison/agent-errors-never-repeat.md"
  - "../../../../gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../rules/module-name-singular-mandatory.md"
  - "./2.1.copy-then-normalize-modules.story.md"
---

# Story 2.2: Documentare errori agente e imporre fase replica singolare

Status: ready-for-dev

## GitHub (tracciamento)

| Tipo | Repo | # | URL |
|------|------|---|-----|
| **Issue** (story owner) | laraxot/base_workorder_fila5 | 9 | https://github.com/laraxot/base_workorder_fila5/issues/9 |
| **Discussion** (story owner) | laraxot/base_workorder_fila5 | 10 | https://github.com/laraxot/base_workorder_fila5/discussions/10 |
| Issue (epic) | laraxot/base_workorder_fila5 | 7 | https://github.com/laraxot/base_workorder_fila5/issues/7 |
| Discussion (epic) | laraxot/base_workorder_fila5 | 8 | https://github.com/laraxot/base_workorder_fila5/discussions/8 |

## Story

As a **platform engineer**,
I want **tutti gli errori degli agenti sul porting gestionale catalogati in `docs/` e trasformati in guardrail operativi**,
so that **nessun agente ripeta delete di repliche (es. AiAssistant) né merge prematuri in User/AI**.

## Acceptance Criteria

1. Esiste [agent-errors-lessons.md](../../../../gestionale-commesse-comparison/agent-errors-lessons.md) (SSoT E1–E10) + alias [agent-errors-never-repeat.md](../../../../gestionale-commesse-comparison/agent-errors-never-repeat.md), con checklist e link GitHub.
2. Canon fase [replica-singular-phase.md](../../../../gestionale-commesse-comparison/replica-singular-phase.md) è l’ingresso obbligatorio nell’indice comparison README.
3. Rule wiki [module-name-singular-mandatory.md](../../../rules/module-name-singular-mandatory.md) distingue **plurale→singolare** da **replica coesistente** (AiAssistant ≠ AI).
4. Docs moduli `AiAssistant`/`Auth`/`HR`/`Email`/`WhatsApp`/`Platform`/`Documents` e Themes non dicono più «cancella / merge ora».
5. `docs/sprint-status.yaml` elenca questa story come `ready-for-dev`.
6. Verifica link GitHub: `bashscripts/ai/verify-bmad-story-github-links.sh` su questo file (o equivalente manuale: issue+discussion URL presenti).

## Tasks / Subtasks

- [x] Catalogo errori in `docs/gestionale-commesse-comparison/agent-errors-never-repeat.md` (AC: #1)
- [x] Allineare replica-singular + mapping + naming rule (AC: #2–3) — già avviati; verificare coerenza
- [x] Harden docs moduli/temi (AC: #4) — già avviati; spot-check anti-pattern «twin-vietato»
- [x] Creare/aggiornare sprint-status + questa story (AC: #5)
- [ ] Spot-check: nessun README modulo GC con testo «merge in X / non creare» residuo (AC: #4)
- [ ] Aggiungere riga in `00-TRIGGER_MAP.md` → agent-errors-never-repeat (second brain)
- [ ] Commento su issue #9 con link discussion #10 + path docs

## Dev Notes

### Religione (perché)

In questa fase **non** c’era collisione di cartelle nwidart: si **replicano** i moduli SRC; unica differenza di naming = **singolare**. Mapping Auth↔User / AiAssistant↔AI è gap **futuro**, non delete.

### Guardrail developer

- Mai `rm` su `laravel/Modules/AiAssistant` (o Auth/HR/…) senza conferma utente sessione
- Prima di edit porting: Read `replica-singular-phase.md` + `agent-errors-never-repeat.md`
- Filament: `getSummarySchema()` = Infolists F5 — [filament-summary-infolist-rule.md](../../../concepts/filament-summary-infolist-rule.md)
- Forward-only: no `git restore`

### Owned scope

- `docs/gestionale-commesse-comparison/agent-errors-never-repeat.md`
- `docs/gestionale-commesse-comparison/replica-singular-phase.md` (link)
- `docs/gestionale-commesse-comparison/README.md`
- `docs/sprint-status.yaml`
- `docs/wiki/skills/bmad-create-story/stories/2.2.agent-errors-replica-singular.story.md`
- `docs/wiki/rules/00-TRIGGER_MAP.md` (1 riga)
- Docs moduli/temi solo se residual «twin-vietato»
- **Non** implementare merge AI/User in questa story

### References

- [Source: agent-errors-never-repeat.md]
- [Source: replica-singular-phase.md]
- [Source: 2.1.copy-then-normalize-modules.story.md] — AC staging merge **superato** dalla correzione utente

## Dev Agent Record

### Agent Model Used

Composer (Cursor Agent)

### Completion Notes List

Ultimate context engine analysis completed - comprehensive developer guide created (lessons E1–E7 + story ready-for-dev).

### File List

- docs/gestionale-commesse-comparison/agent-errors-never-repeat.md
- docs/wiki/skills/bmad-create-story/stories/2.2.agent-errors-replica-singular.story.md
- docs/sprint-status.yaml

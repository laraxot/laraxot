---
title: "Task Observer — meta-skill auto-miglioramento"
module: "xot"
type: reference
status: approved
tags: [ai, skill, meta-skill, self-improving, observations, augmented-expertise]
created: 2026-08-19
updated: 2026-08-19
qmd: "task observer meta-skill one skill rule them all self-improving observations corrections skill library augmented expertise rebelytics"
related:
  - "./ponytail.md"
  - "./bmad-method.md"
  - "./claude-mem.md"
---

# Task Observer (One Skill to Rule Them All)

> [github.com/rebelytics/one-skill-to-rule-them-all](https://github.com/rebelytics/one-skill-to-rule-them-all) — CC BY 4.0

## Scopo

Meta-skill che osserva le sessioni di lavoro, cattura correzioni e judgment call,
e le trasforma in miglioramenti delle skill. Si auto-migliora.

## Cosa fa

1. **Identifica nuove skill** — pattern ripetuti → candidati skill
2. **Migliora skill esistenti** — correzioni, preferenze, gap → aggiornamenti
3. **Auto-miglioramento** — osserva se stesso e migliora la propria metodologia

## Come funziona

- Monitora correzioni e aggiustamenti durante la sessione
- Identifica gap non coperti da skill esistenti
- Produce log strutturato: cosa notato, skill coinvolte, miglioramenti suggeriti
- L'utente rivede e approva → skill evolvono

## Cross-cutting principles

Osservazioni che attraversano più skill vengono catturate separatamente e usate
come quality floor per tutte le nuove skill.

## Installazione

**Installato** in `/home/zorin/.cursor/skills-cursor/task-observer/`

Struttura:
- `SKILL.md` — skill core (446 righe)
- `references/skill-authoring.md` — tassonomia, licenze, editing rules
- `references/weekly-review.md` — procedura review periodica
- `references/environments.md` — setup per ambienti diversi

Observation log: `/home/zorin/.claude/skill-observations/log.md`

## Integrazione progetto

Complementare al nostro sistema `docs/chat/` (handoff tra agenti) e alle
rules on-demand (`.cursor/rules/`). Task Observer automatizza il ciclo:
osservazione → proposta miglioramento → review → applicazione.

**900+ miglioramenti** applicati in 6 mesi di uso dall'autore.

## Workflow consigliato

1. Skill caricata in tutte le sessioni
2. A fine sessione: "Any observations logged?"
3. Review periodica (2-3x settimana) per applicare suggerimenti

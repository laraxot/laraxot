---
title: "BMad Method — agile AI-driven development"
module: "xot"
type: reference
status: approved
tags: [ai, bmad, agile, methodology, planning, claude-code, codex]
created: 2026-08-19
updated: 2026-08-19
qmd: "bmad method agile ai driven development planning clarify plan build verify learn adjust workflow agents specialized"
related:
  - "./ponytail.md"
  - "./claude-mem.md"
  - "./claude-code-setup.md"
  - "./omniroute.md"
---

# BMad Method

> [github.com/bmad-code-org/bmad-method](https://github.com/bmad-code-org/bmad-method) — MIT

## Scopo

Metodo agile per AI-Driven Development. Trasforma un'idea o change request in software
funzionante mantenendo le decisioni esplicite e il contesto forward.

## Delivery loop

```
Clarify → Plan → Build & Verify → Learn & Adjust → (loop)
```

- **Vague notion** → inizia da Clarify
- **Big clear idea** → inizia da Plan
- **Small change** → inizia da Build

## Moduli ecosistema

| Modulo | Scopo |
|--------|-------|
| BMad Method | Core: plan & deliver software |
| BMad Builder | Costruttore di skill, workflow, agenti |
| BMad Creative Intelligence | Design thinking, innovazione |
| BMad Test Architect | Testing enterprise |
| BMad Loop | Epic unattended: build + verify + retro |
| BMad Game Dev Studio | Game dev (Unity, Unreal, Godot, Phaser) |

## Installazione

```bash
npx bmad-method install
```

Prerequisiti: Node.js 20.12+, Python 3.10+, uv.

## Integrazione progetto

**Già in uso**: questo progetto usa BMad internamente tramite:
- `.claude/skills/bmad-planning-orchestrator/` — skill locali
- `.codex/skills/bmad-*` — skill Codex
- Subagent types: `epic-scoper`, `story-author`, `readiness-auditor`, `gsd-*`

La versione ufficiale v6 (npm) è la upstream; le skill locali sono un fork
customizzato per il workflow Laraxot. Aggiornare periodicamente.

## Perché BMad

- **Right-sized process** — non obbliga a fare planning su fix banali
- **Durable context** — decisioni product/tech portate avanti, non ri-spiegate
- **Specialized perspectives** — product, architecture, UX, dev, test
- **Guided collaboration** — workflow strutturati senza cedere il giudizio

## Riferimenti

- [Docs ufficiali](https://docs.bmad-method.org/)
- [Articolo italiano](https://pasqualepillitteri.it/news/170/bmad-framework-claude-code-sviluppo-agile)
- [Discord community](https://discord.gg/gk8jAdXWmj)

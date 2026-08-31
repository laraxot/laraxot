---
title: "Claude-Mem — memoria persistente cross-sessione per agenti AI"
module: "xot"
type: reference
status: approved
tags: [ai, memory, persistent, cross-session, claude-code, context, compression]
created: 2026-08-19
updated: 2026-08-19
qmd: "claude-mem memoria persistente sessione agente ai contesto compressione hook worker sqlite fts5 chroma vector search observation"
related:
  - "./ponytail.md"
  - "./omniroute.md"
  - "./claude-code-setup.md"
---

# Claude-Mem

> [github.com/thedotmack/claude-mem](https://github.com/thedotmack/claude-mem) — Apache-2.0

## Scopo

Sistema di memoria persistente cross-sessione per agenti AI. Cattura automaticamente
osservazioni durante le sessioni, le comprime con AI, e le inietta nelle sessioni future.

## Come funziona

1. **5 Lifecycle Hooks** — SessionStart, UserPromptSubmit, PostToolUse, Stop, SessionEnd
2. **Worker Service** — API HTTP locale con web viewer UI
3. **SQLite + FTS5** — storage osservazioni e ricerca full-text
4. **Chroma Vector DB** — ricerca semantica ibrida
5. **mem-search Skill** — query natural language con progressive disclosure

## Workflow 3 layer (token-efficient)

1. `search` — indice compatto (~50-100 token/risultato)
2. `timeline` — contesto cronologico attorno ai risultati
3. `get_observations` — dettagli completi solo per ID filtrati (~500-1000 token/risultato)

Risparmio ~10x token rispetto a fetch completo.

## Installazione

```bash
npx claude-mem install
```

O via plugin marketplace:

```
/plugin marketplace add thedotmack/claude-mem
/plugin install claude-mem
```

## Integrazione progetto

Complementare al second brain su disco (`docs/chat/`, `docs/wiki/`) già in uso.
Claude-Mem aggiunge:

- **Continuità automatica** tra sessioni senza handoff manuale
- **Ricerca semantica** su tutta la storia del progetto
- **Privacy control** con tag `<private>` per escludere contenuti sensibili
- **Progressive disclosure** per controllare il costo token

## Confronto con second brain attuale

| Aspetto | Second brain (docs/) | Claude-Mem |
|---------|---------------------|------------|
| Storage | file .md su disco | SQLite + vector |
| Ricerca | QMD / grep | FTS5 + Chroma semantica |
| Injection | manuale (bootstrap) | automatica (hooks) |
| Cross-sessione | handoff .md | trasparente |
| Git-tracked | sì | no (locale) |

I due approcci sono **complementari**: docs/ per decisioni architetturali versionabili,
Claude-Mem per contesto operativo effimero tra sessioni.

## Requisiti

- Node.js 20+
- Bun (auto-installato)
- Claude Code con supporto plugin

---
title: "FindSkills — directory 94k+ skill AI open-source"
module: "xot"
type: reference
status: approved
tags: [ai, skills, directory, mcp, search, install, findskills]
created: 2026-08-19
updated: 2026-08-19
qmd: "findskills directory 94000 skill ai open source mcp search install claude cursor codex github clawhub openclaw"
related:
  - "./ponytail.md"
  - "./task-observer.md"
  - "./bmad-method.md"
---

# FindSkills

> [findskills.org](https://www.findskills.org/) — Directory di 94k+ skill AI open-source

## Scopo

Motore di ricerca e directory per skill AI: MCP servers, Claude Code skills,
GPT plugins. Indicizza GitHub, ClawHub, OpenClaw.

## Installazione MCP

```bash
npx findskills-mcp init
```

Auto-rileva Claude Code, Claude Desktop, o Cursor e configura.

## API endpoints

| Endpoint | Uso |
|----------|-----|
| `GET /api/v1/search?q={keyword}` | Ricerca skill |
| `GET /api/v1/skills` | Lista e filtra |
| `GET /api/v1/openapi` | Spec OpenAPI |
| `GET /llms.txt` | Contesto LLM |

## CLI

```bash
npx findskills "web scraping"  # cerca e installa da terminale
```

## Integrazione progetto

Utile per scoprire nuove skill MCP da integrare. Attualmente usiamo:
- `context7` (docs)
- `playwright` (browser testing)
- `qmd` (markdown search locale)
- `laravel-boost` (Laravel ecosystem)

FindSkills può rivelare alternative o complementi a queste.

## Autenticazione

```bash
npx findskills auth  # login GitHub, provisioning API key (gratuito)
```

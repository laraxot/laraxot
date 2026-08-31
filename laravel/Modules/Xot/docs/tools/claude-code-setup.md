---
title: "Claude Code Setup Plugin — analisi e raccomandazione automazioni"
module: "xot"
type: reference
status: approved
tags: [ai, claude-code, plugin, hooks, skills, mcp, subagents]
created: 2026-08-19
updated: 2026-08-19
qmd: "claude code setup plugin analisi codebase hooks skills mcp subagents slash commands raccomandazioni automazioni"
related:
  - "./ponytail.md"
  - "./claude-mem.md"
  - "./omniroute.md"
---

# Claude Code Setup Plugin

> [github.com/anthropics/claude-plugins-official/.../claude-code-setup](https://github.com/anthropics/claude-plugins-official/tree/main/plugins/claude-code-setup) — Anthropic

## Scopo

Plugin ufficiale Anthropic per Claude Code. Scansiona il codebase e raccomanda le
migliori 1–2 automazioni per categoria:

- **MCP Servers** — integrazioni esterne (context7 per docs, Playwright per frontend)
- **Skills** — pacchetti di competenza (Plan agent, frontend-design)
- **Hooks** — azioni automatiche (auto-format, auto-lint, block file sensibili)
- **Subagents** — reviewer specializzati (security, performance, accessibility)
- **Slash Commands** — workflow rapidi (/test, /pr-review, /explain)

## Caratteristiche

- **Read-only**: analizza ma non modifica
- **Context-aware**: le raccomandazioni dipendono dallo stack rilevato

## Utilizzo

```
"recommend automations for this project"
"help me set up Claude Code"
"what hooks should I use?"
```

## Integrazione progetto

Utile per validare periodicamente che hooks, skills e MCP del progetto siano
allineati alle best practice. Complementare al bootstrap on-demand già in uso.

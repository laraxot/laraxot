---
type: story
tags: [claude-cowork, plugins, agents, skills, mcp, standardization]
created: 2026-10-14
updated: 2026-10-14
qmd: "Claude Cowork plugins struttura agenti skills MCP standard"
issues: []
discussions: []
id: "2026-10-14-dcp-163"
epic: 163
title: "Standardizzazione agenti/skills ispirata Claude Cowork Plugins"
slug: agenti-skills-standard-cowork
status: ready-for-dev
scope: repo
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/63
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/64
  issue_status: "aperta"
related:
  - ../../Xot/docs/stories/phpstan-cleanup-xot-app.story.md
  - ../../bashscripts/ai/wiki/rules/00-ON_DEMAND_PATTERN.md
---

# 2026-10-14 DCP-163 Standardizzazione agenti/skills ispirata Claude Cowork Plugins

## Fonte

`https://pasqualepillitteri.it/en/news/200/claude-cowork-plugins-complete-guide-professionals` (02/02/2026).

## Principi chiave

1. **Plugin = pacchetto file-based**: `plugin.json` (metadata) + `mcp.json` (MCP connectors) + `commands/` (slash commands) + `skills/` (domain knowledge + Python functions).
2. **Zero build, zero infrastruttura**: solo Markdown e JSON.
3. **Plugin Manager (meta-plugin)**: crea plugin da descrizioni naturali.
4. **MCP connectors**: integrano CRM, data warehouse, wiki (es. Confluence, Notion).
5. **Desktop Extensions (.mcpb)**: un click, SSO, senza VPN.

## Applicabilità al progetto

- I nostri agenti Kilo sono già in formato file-based (`.kilo/agent/*.md`, `.kilo/command/*.md`).
- Il progetto usa già MCP (qmd, token-optimizer).
- **Guadagno concreto**: standardizzare la struttura agenti con metadata frontmatter (come `plugin.json`) e aggiungere un agente "architetto" (meta-agent) che genera agenti/skills su richiesta.

## Accettazione

1. Pagina wiki con la mappa della struttura plugin consigliata.
2. Agente "architetto" (meta-agent) che genera skills/commands da descrizioni naturali.
3. Nessun file creativo in `app/Http/Controllers`.
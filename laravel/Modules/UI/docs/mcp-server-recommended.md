---
title: "MCP Server Consigliati per il Modulo UI"
type: concept
tags: [mcp, server, recommended]
created: 2026-07-14
updated: 2026-07-14
qmd: "mcp-server-recommended mcp server consigliati per il modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# MCP Server Consigliati per il Modulo UI

## Scopo del Modulo
Gestione interfaccia utente, componenti, asset e frontend.

## Server MCP Consigliati
- `filesystem`: Per gestione asset, immagini, file statici.
- `fetch`: Per recupero dati dinamici da API.
- `memory`: Per stato temporaneo dell'interfaccia (es. wizard, step form).

## Configurazione Minima Esempio
```json
{
  "mcpServers": {
    "filesystem": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-filesystem"] },
    "fetch": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-fetch"] },
    "memory": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-memory"] }
  }
}
```

## Note
- Personalizza la configurazione per esigenze di frontend avanzato.

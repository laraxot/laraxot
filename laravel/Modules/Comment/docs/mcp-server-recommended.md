---
title: "mcp server recommended"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "mcp server recommended"
issues: []
discussions: []
status: draft
---

# MCP Server Consigliati per il Modulo Comment

## Scopo del Modulo
Gestione commenti, moderazione e interazioni utente.

## Server MCP Consigliati
- `memory`: Per gestire sessioni utente e stato temporaneo dei commenti.
- `fetch`: Per integrazione con servizi di moderazione esterni o API di terze parti.

## Configurazione Minima Esempio
```json
{
  "mcpServers": {
    "memory": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-memory"] },
    "fetch": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-fetch"] }
  }
}
```

## Note
- Personalizza la configurazione per esigenze di moderazione avanzata.

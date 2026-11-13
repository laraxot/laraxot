---
title: "mcp server consigliati"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "mcp server consigliati"
issues: []
discussions: []
status: draft
---

# Server MCP consigliati per il modulo Comment

## Scopo del modulo
Gestione e moderazione dei commenti, automazione di filtri e analisi testo.

## Server MCP consigliati
- **fetch**: Per recuperare dati esterni utili alla moderazione (es. blacklist, API antispam).
- **memory**: Per mantenere stato tra sessioni di moderazione o analisi.
- **everything**: Per avere tutte le funzionalità MCP disponibili.

## Esempio di configurazione MCP
```json
{
  "mcpServers": {
    "fetch": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-fetch"] },
    "memory": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-memory"] },
    "everything": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-everything"] }
  }
}
```

**Nota:**
Aggiungi solo i server che realmente ti servono per il tuo workflow. 

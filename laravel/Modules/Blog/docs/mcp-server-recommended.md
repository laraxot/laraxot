# MCP Server Consigliati per il Modulo Blog

## Scopo del Modulo
Gestione di blog, articoli, categorie, commenti e contenuti editoriali.

## Server MCP Consigliati
- `filesystem`: Per operazioni su file allegati, immagini e media degli articoli.
- `fetch`: Per integrazione con API esterne (es. importazione articoli, commenti da servizi terzi).
- `memory` (opzionale): Per cache temporanea di contenuti editoriali o sessioni di editing.

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
- Personalizza la configurazione in base alle esigenze di import/export o automazioni editoriali.

---
title: "MCP Servers Configuration"
type: concept
tags: [mcp, servers]
created: 2026-07-14
updated: 2026-07-14
qmd: "mcp-servers mcp servers configuration"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./2-1-1-plan.md"
  - "./2-1-context.md"
  - "./agents.md"
  - "./ai-agent-lessons-learned.md"
  - "./ai-skills-and-plugins-complete.md"
  - "./commit-message.md"
  - "./configuration.md"
  - "./design-comuni-bmad-master-plan.md"
---

# MCP Servers Configuration

**Ultimo aggiornamento**: 2026-04-09  
**Canonical Config**: `laravel/.mcp.json`  
**Total Servers**: 9 (6 existing + 3 new memory servers)

## 📚 Canonical Documentation

⚠️ **DRY Rule**: Questa pagina è un index. Per documentazione completa, vedere:

| Document | Location |
|----------|----------|
| **⭐ MCP Servers Index** | [Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md](../../laravel/Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md) |
| **Theme MCP Setup** | [Themes/Sixteen/docs/mcp/MCP-THEME-SETUP.md](../../laravel/Themes/Sixteen/docs/mcp/MCP-THEME-SETUP.md) |
| **Bash Scripts MCP Index** | [bashscripts/docs/mcp/MCP-INDEX.md](../../bashscripts/docs/mcp/MCP-INDEX.md) |
| **Memory Bank** | [.memory-bank/](../.memory-bank/) |

## 🧠 Memory Servers (NEW - 2026-04-09)

### 1. Knowledge Graph Memory (Official)
- **Package**: `@modelcontextprotocol/server-memory`
- **Scopo**: Persistent memory across sessions
- **Storage**: Local JSON
- **Status**: ✅ Attivo

### 2. Memory Bank (⭐893)
- **Package**: `memory-bank-mcp`
- **Scopo**: Structured project memory (Cline pattern)
- **Storage**: `.memory-bank/` directory
- **Files**: 5/5 completati
  - activeContext.md
  - productContext.md
  - techContext.md
  - systemPatterns.md
  - progress.md
- **Status**: ✅ Attivo

### 3. Context7 (⭐52,094)
- **Package**: `@upstash/context7-mcp`
- **Scopo**: Code documentation lookup
- **API Key**: Richiesta (gratuita)
- **Status**: ✅ Configurato

## 🛠️ Existing Servers (6)

| Server | Scopo | Status |
|--------|-------|--------|
| laravel-boost | Artisan commands, docs | ✅ |
| filesystem | File operations | ✅ |
| sqlite | Database queries | ✅ |
| sequential-thinking | Reasoning | ✅ |
| fetch | HTTP requests | ✅ |
| github | Git management | ✅ |

## 🚀 Quick Start

```bash
# Config: laravel/.mcp.json
# Memory Bank: .memory-bank/

# Riavviare IDE per ricaricare MCP
# Verifica: Test memory tools in chat
```

## 📊 Full Documentation

Per dettagli completi su configurazione, usage, e best practices:

→ [MCP-SERVERS-INDEX.md](../../laravel/Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md)

## Related Docs

- [Project Configuration](project/configuration.md)
- [AI Workflow](project/ai-workflow/)
- Module MCP: [MCP-SERVERS-INDEX.md](../../laravel/Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md)
- Theme MCP: [MCP-THEME-SETUP.md](../../laravel/Themes/Sixteen/docs/mcp/MCP-THEME-SETUP.md)
- Bash Scripts MCP: [MCP-INDEX.md](../../bashscripts/docs/mcp/MCP-INDEX.md)

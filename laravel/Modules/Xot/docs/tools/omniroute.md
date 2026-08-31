---
title: "OmniRoute — AI gateway multi-provider"
module: "xot"
type: reference
status: approved
tags: [ai, gateway, multi-provider, fallback, token-compression, mcp]
created: 2026-08-19
updated: 2026-08-19
qmd: "omniroute ai gateway free mit 340 providers 1200 models quota fallback rtk caveman compression mcp a2a claude gpt gemini deepseek"
related:
  - "./ponytail.md"
  - "./claude-mem.md"
---

# OmniRoute

> [github.com/diegosouzapw/OmniRoute](https://github.com/diegosouzapw/OmniRoute) — MIT

## Scopo

Gateway AI unico endpoint, 340+ provider (90+ free), 1200+ modelli. Compatibile con
Claude Code, Codex, Cursor, OpenCode, Cline, Copilot.

## Perché ci interessa

- **Quota-aware auto-fallback**: se un provider è saturo, ruota sul successivo
- **RTK + Caveman compression**: risparmio 15–95% token
- **MCP/A2A**: integrazione protocollo standard
- **Desktop/PWA**: GUI per gestione chiavi e routing

## Installazione tipica

Endpoint unico configurabile come `OPENAI_BASE_URL` nei tool AI:

```env
OPENAI_BASE_URL=https://omniroute.local/v1
OPENAI_API_KEY=your-key
```

## Integrazione progetto

Utile come layer di resilienza per gli agenti AI che lavorano su questo repo.
Il token-saving stack (RTK + Caveman) è complementare a Ponytail (riduce codice generato)
e Claude-Mem (riduce contesto iniettato).

## Riferimenti

- 50k+ stars, 450+ contributor
- Supporta Kimi, Claude, GPT, Gemini, GLM, DeepSeek, MiniMax

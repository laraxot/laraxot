---
title: "ds4 CLI Integrations Reference"
type: reference
tags: [ds4, integration, cli, opencode, claude-code, kilo, agent]
qmd: true
---

# ds4 CLI Integrations Reference

DeepSeek V4 Flash local inference engine per coding agents.

## Architecture

- **ds4-server**: HTTP API server OpenAI/Anthropic-compatible
- **Backend**: Metal (macOS), CUDA (Linux), ROCm (Strix Halo)
- **SSD Streaming**: Modelli > RAM
- **Distributed**: Multi-machine inference

## Quick Start

```bash
cd /var/www/ds4
./start-for-integration.sh 8000 32768 127.0.0.1
# Server: http://127.0.0.1:8000
```

## Endpoints

```
POST /v1/chat/completions    # OpenAI (opencode, kilo)
POST /v1/messages           # Anthropic (claude-code)
POST /v1/responses          # Anthropic/Codex
GET  /v1/models             # Model list
```

## Claude Code

Wrapper script: `~/bin/claude-ds4`

```bash
#!/bin/sh
unset ANTHROPIC_API_KEY
export ANTHROPIC_BASE_URL="http://127.0.0.1:8000"
export ANTHROPIC_AUTH_TOKEN="dsv4-local"
export ANTHROPIC_MODEL="deepseek-v4-flash"
export CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1
export CLAUDE_CODE_DISABLE_NONSTREAMING_FALLBACK=1
export CLAUDE_STREAM_IDLE_TIMEOUT_MS=600000
exec claude "$@"
```

**Start server:** `./start-ds4-for-claude.sh --fg`

## OpenCode CLI

`~/.config/opencode/opencode.json`

```json
{
  "provider": {
    "ds4": {
      "name": "ds4.c (local)",
      "npm": "@ai-sdk/openai-compatible",
      "options": {
        "baseURL": "http://127.0.0.1:8000/v1",
        "apiKey": "dsv4-local"
      },
      "models": {
        "deepseek-v4-flash": {
          "name": "DeepSeek V4 Flash (local)",
          "limit": {"context": 100000, "output": 384000}
        }
      }
    }
  },
  "agent": {
    "ds4": {
      "description": "DeepSeek V4 Flash via ds4.c",
      "model": "ds4/deepseek-v4-flash",
      "temperature": 0
    }
  }
}
```

## Kilo CLI

Variabili ambientali:

```bash
export OPENAI_API_BASE="http://127.0.0.1:8000/v1"
export OPENAI_API_KEY="dsv4-local"
```

## Agent CLI (Pi)

`~/.pi/agent/models.json`

```json
{
  "providers": {
    "ds4": {
      "name": "ds4.c local",
      "baseUrl": "http://127.0.0.1:8000/v1",
      "api": "openai-completions",
      "apiKey": "dsv4-local",
      "compat": {
        "thinkingFormat": "deepseek",
        "requiresReasoningContentOnAssistantMessages": true,
        "supportsReasoningEffort": true,
        "supportsUsageInStreaming": true,
        "maxTokensField": "max_tokens"
      },
      "models": [{
        "id": "deepseek-v4-flash",
        "name": "DeepSeek V4 Flash (local)",
        "reasoning": true,
        "contextWindow": 100000,
        "maxTokens": 384000,
        "cost": {"input": 0, "output": 0, "cacheRead": 0, "cacheWrite": 0}
      }]
    }
  }
}
```

## Model Download

```bash
./download_model.sh q2-imatrix    # 96/128GB RAM
./download_model.sh q4-imatrix    # >=256GB RAM
./download_model.sh pro-q2-imatrix  # 512GB PRO
```

## SSD Streaming ( Memory > RAM)

```bash
./ds4-server --ssd-streaming --ssd-streaming-cache-experts 32GB
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Connection refused | `curl http://127.0.0.1:8000/v1/models` |
| Memory error | Reduce context o abilita SSD streaming |
| Model not found | Check symlink `ls -la ds4flash.gguf` |
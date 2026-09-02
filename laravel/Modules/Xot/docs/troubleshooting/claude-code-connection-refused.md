---
title: "Claude Code: Connection refused (ConnectionRefused)"
type: troubleshooting
tags: [claude-code, omniroute, connection-refused, proxy]
created: 2026-09-01
updated: 2026-09-01
qmd: "claude code connection refused firewall proxy omniroute 20128 ANTHROPIC_BASE_URL"
related:
  - ../tools/omniroute.md
  - ../stories/5.43.omniroute-doctor-warnings-triage.story.md
---

# Claude Code: `Connection refused (ConnectionRefused)`

## Sintomo

```
API Error: Connection refused — a firewall or proxy may be blocking it (ConnectionRefused)
```

## Causa (PTVX / Laraxot)

Quasi sempre **OmniRoute non in ascolto** su `127.0.0.1:20128` mentre Claude Code usa un profilo con:

```json
"ANTHROPIC_BASE_URL": "http://127.0.0.1:20128"
```

I profili sono in `~/.claude/profiles/<nome>/settings.json` (655 profili generati da `omniroute setup-claude`).

`omniroute launch` **non** avvia il server: se OmniRoute è spento, fallisce con lo stesso errore.

Sessione **senza** profilo OmniRoute → API diretta `api.anthropic.com` (funziona se loggati con `/login`).

## Fix rapido

```bash
# 1. Verifica / avvia OmniRoute
bash bashscripts/tools/omniroute-ensure.sh

# 2. Preflight prima di claude
bash bashscripts/tools/claude-code-preflight.sh

# 3. Oppure riavvio manuale
omniroute restart
omniroute health
```

Con profilo OmniRoute:

```bash
bash bashscripts/tools/omniroute-ensure.sh
omniroute launch --profile no-think-claude-claude-sonnet-4-6-medium
```

Senza gateway (API Anthropic diretta):

```bash
unset ANTHROPIC_BASE_URL ANTHROPIC_AUTH_TOKEN
claude
```

## Autostart

OmniRoute ha autostart XDG (`~/.config/autostart/omniroute.desktop`). In WSL può partire **dopo** la prima sessione terminale:

```bash
omniroute autostart status   # enabled + linger
omniroute autostart enable   # se disabilitato
```

## Trapola DS4 (porta 8000)

Non confondere con `ANTHROPIC_BASE_URL=http://127.0.0.1:8000` da `.claude/scripts/ds4-launch.sh`: la 8000 può essere occupata da `php artisan serve` (Laravel), non da un proxy Anthropic.

## Verifica

```bash
ss -tlnp | grep 20128
curl -sf http://127.0.0.1:20128/ && echo OK
omniroute doctor
```

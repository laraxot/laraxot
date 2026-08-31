---
title: Junction IDE e agenti — SSoT .agents
type: guide
created: 2026-07-28
updated: 2026-07-28
tags: [ai, agents, ide, junction, ssot]
---

# Junction IDE e agenti

## Scopo

Un solo harness condiviso per Cursor, Claude, Windsurf, Codex e gli altri adapter locali.
La configurazione reale vive in `bashscripts/ai/.agents/`; nella root del repo restano solo **symlink** verso quella directory.

## SSoT e shadow vietate

| Elemento | Percorso |
|----------|----------|
| Config/prompt agenti | `bashscripts/ai/.agents/` |
| Wiki rules/skills/memories | `bashscripts/ai/wiki/` |

**Vietato** creare copie shadow sotto `bashscripts/ai/` (es. `bashscripts/ai/.cursor`, `bashscripts/ai/.claude`).

## Junction root (verifica/riparazione)

```bash
bash bashscripts/tools/sync-ide-junctions.sh --check
bash bashscripts/tools/sync-ide-junctions.sh
```

Nomi gestiti dallo script: `.agent`, `.claude`, `.devin`, `.codex`, `.opencode`, `.cursor`, `.gemini`, `.iflow`, `.junie`, `.windsurf`, `.zai`, `.agents`, `.kilo`.

`.github/prompts` punta a `bashscripts/ai/.agents/prompts` (non alla root `.agents`). Dettaglio: [concepts/ai-agent-prompts-junction.md](concepts/ai-agent-prompts-junction.md).

## Wiki (rules, skills, memories)

```bash
bash bashscripts/tools/sync-wiki-junctions.sh --check
bash bashscripts/tools/sync-wiki-junctions.sh
```

Ogni sottocartella in `bashscripts/ai/wiki/` deve essere esposta come `docs/wiki/<nome>` tramite symlink.

## Script obsoleto

`bashscripts/ai/ai_init.sh` implementava il modello **shadow per IDE** (una cartella reale per tool sotto `bashscripts/ai/`). Non usarlo: sostituito da `sync-ide-junctions.sh` e `sync-wiki-junctions.sh`.

## Cross-reference

- [docs/wiki/concepts/agent-config-junctions-ssot.md](../../../../docs/wiki/concepts/agent-config-junctions-ssot.md)
- [concepts/ai-agent-prompts-junction.md](concepts/ai-agent-prompts-junction.md)
- `bashscripts/docs/second-brain-healthcheck.sh` — include check junction quando disponibili

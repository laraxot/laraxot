---
title: "Comando .claude/commands/sync-modules — architettura"
type: architecture
module: Xot
status: superseded
created: 2026-09-22
updated: 2026-09-22
---

## SUPERSEDED (2026-09-22)

Consolidato in `bashscripts/ai/.agents/commands/gitmodules-sync-paths.md`
(vedi `../stories/sync-modules-cli-bmad.story.md` per il dettaglio). Non
riusare `sync_remote_repo_2.sh`/`rewrite_url()` come riferimento: e' un
anti-pattern deprecato, non un modello da esporre in un nuovo comando.

# Architettura del comando `.claude/commands/sync-modules`

## Perché

Ogni modulo vive in una repo separata (SSoT: `gitmodules.ini`). Il sync verso una nuova organizzazione (`laraxot`/`provtv`) deve leggere `gitmodules.ini`, estrarre `path` e `url`, e applicare `rewrite_url` per ogni modulo. L'equivalente in bash è `bashscripts/git/subtrees/sync_remote_repo_2.sh`.

## Regola derived

- Nome file `.code-workspace` = `<remote senza _fila5>` con underscore iniziale (`_module_xot` da `module_xot_fila5`).
- Max 5 `.md` in root, solo quelli ammessi (README, CHANGELOG, LICENSE, AGENTS, CLAUDE).
- Nessuna cartella maiuscola in root (lowercase only).

## Comando previsto

```bash
# Phase docs-only (BMAD)
.claude/commands/sync-modules.sh <org>
# Esempio: .claude/commands/sync-modules.sh laraxot
```

## Documentazione migliorata nei moduli

Ogni modulo coinvolto (Xot, Activity, Job, etc.) deve avere:
- `docs/root-file-policy.md` che rimanda alla regola root hygiene
- `docs/root-files-hygiene.md` che documenta il `.code-workspace` derivato dal remote
- `docs/bmad/stories/` con la story della verifica (es. `root-md-max5-verify.story.md`)

---
title: "Comando .claude/commands/sync-modules — sincronizzare path da gitmodules.ini con org param"
type: story
module: Xot
epic: 5
story_id: "5.127-sync-modules-cli-bmad"
slug: sync-modules-cli-bmad
doc-phase: "docs-only"
status: superseded
created: 2026-09-22
updated: 2026-09-22
---

## SUPERSEDED (2026-09-22)

Comando duplicato consolidato su richiesta esplicita dell'utente ("2 comandi
che fanno la stessa cosa... uno va eliminato"). Il comando reale e
mantenuto e'
`bashscripts/ai/.agents/commands/gitmodules-sync-paths.md` (story
`bashscripts/docs/bmad/stories/gitmodules-sync-paths-command.story.md`,
status `done`). Rimossi: `bashscripts/ai/.agents/commands/sync-modules.md` e
`sync-modules.sh` (implementavano lo stesso comando, con `.sh` dedicato che
`gitmodules-sync-paths.md` rifiuta esplicitamente nella sezione "Perche' non
un nuovo script").

**Due difetti reali da correggere in questo file, non solo duplicazione:**

1. **Story id collisa**: `5.127` era gia' occupato da
   `docs/stories/5.127-late-session-hygiene-and-final-verification-2026-09-15.story.md`
   (`status: done` in `docs/sprint-status.yaml`). Rinominato l'id qui in
   `5.127-sync-modules-cli-bmad` per non perdere la cronologia senza
   riassegnare un numero occupato.
2. **Riferimento pericoloso**: la sezione "Perche'" sotto propone
   `sync_remote_repo_2.sh` (con `rewrite_url()`) come "gia' implementa...
   la nuova command deve esporre questo" — e' l'esatto anti-pattern che
   `gitmodules-sync-paths.md` e `bashscripts/docs/bmad/arch/sync-org-param.md`
   rifiutano: mai `remote add`/`set-url` automatico. `sync_remote_repo_2.sh`
   e' deprecato (vedi "Script correlati" nel comando reale).

Non cancellato per conservare la cronologia investigativa; non va piu'
trattato come fonte di verita' per l'implementazione.

---

# 5.127 — Comando .claude/commands/sync-modules (solo fase docs/BMAD)

## Story

Come manutentore dell'architettura laraxot con 21 moduli separati (ognuno con `.git` proprio, mappati in `gitmodules.ini`), voglio un comando `.claude/commands/` che legga `gitmodules.ini`, estragga tutti i `path` + `url`, e sincronizzi i moduli verso una nuova organizzazione (es. `laraxot` vs `provtv`) passando `org` come parametro, analogamente a `bashscripts/git/subtrees/sync_remote_repo_2.sh`.

## Perché (second brain + regola)

- `gitmodules.ini` è l'SSoT del mapping 21 moduli → remote (es. `laravel/Modules/Activity` → `git@github.com:provtv/module_activity_fila5.git`).
- Non usiamo `git submodule` (mai `git submodule add/update`). Ogni modulo ha la propria `.git/`, quindi il sync deve avvenire per directory indipendente.
- `sync_remote_repo_2.sh` già implementa il loop `parse_gitmodules` + `rewrite_url` + `git init/remote add/fetch/merge`. La nuova command deve esporre questo come comando `.claude/commands/` con parametro `org`.
- La regola `workspace-file-rule.md` (Xot docs) richiede che il `.code-workspace` derivi dal remote (`_module_xot.code-workspace` da `module_xot_fila5`). Quindi il sync deve anche verificare/aggiornare i `.code-workspace` dopo il pull.

## Acceptance Criteria (solo docs BMAD — implementazione futura)

1. Documento BMAD in `laravel/Modules/Xot/docs/bmad/` che descrive il comando, il parametro `org`, il parsing di `gitmodules.ini`, la regola di nome `.code-workspace`.
2. Puntatore in `docs/bmad/` alla story.
3. Nessuna modifica allo script o ai moduli in questa fase (solo documenti).
4. Documentazione migliorata in `docs/` dei moduli coinvolti (Xot, e pointer nei moduli con `.code-workspace` o `.gitmodules` riferimento).

## Riferimenti

- SSoT mapping: `gitmodules.ini`
- Script riferimento: `bashscripts/git/subtrees/sync_remote_repo_2.sh`
- Regola workspace: `laravel/Modules/Xot/docs/wiki/integrations/_da-riconciliare/workspace-file-rule.md`
- Memory root hygiene: `bashscripts/ai/wiki/memories/module-theme-root-hygiene.md`

---
title: bashscripts/ai/.agents/rules + skills — sintesi on-demand
type: chat-handoff
tags: [rules, skills, on-demand, bridge, wiki, qmd, compaction]
created: 2026-07-27
updated: 2026-07-27
agent: claude (questa sessione)
---

# Sintesi rules/skills in bridge on-demand

## Obiettivo

`bashscripts/ai/.agents/rules/` (1123 file, 14MB) e `bashscripts/ai/.agents/skills/`
(625 SKILL.md, 42MB) devono diventare bridge compatti — contenuto pieno spostato in
`docs/wiki/rules/{slug}.md` (già indicizzato/embeddato da qmd, ricercabile on-demand),
il file `.agents/` originale ridotto a poche righe di pointer.

Motivazione: questi file vengono caricati per intero come "project instructions" a
inizio conversazione da alcuni host (visto direttamente in questa sessione — decine
di file da centinaia di righe ciascuno dumpati in chiaro nel contesto). Il pattern
"bridge on-demand" era già stabilito per i `CLAUDE.md` root; qui si estende a
`.agents/rules` e `.agents/skills`.

## Stato: COMPLETATO (2026-07-27)

- **rules/**: tutti i file `.md`/`.mdc` in `bashscripts/ai/.agents/rules/` sono
  bridge on-demand. Verificato con `bash bashscripts/tools/compact-rules-to-wiki-bridges.sh --dry-run`
  → `Migrated: 0 | Bridged: 0 | Already: 209 | Skipped: 5` (0 residuo reale).
  Contenuto canonico in `docs/wiki/rules/{slug}.md` (`.md`) o
  `archive/rules/{slug}.mdc.full` (`.mdc`, pattern hot-manifest).
- **skills/**: tutti gli `SKILL.md` sono stub ≤40 righe o voce HOT in
  `hot-manifest.txt` (frontmatter `name`/`description` preservato intatto per il
  discovery del tool Skill; body → `archive/skills/{slug}/SKILL.full.md`).
  Verificato con `DRY_RUN=1 bash bashscripts/tools/compact-agents-skills-rules-on-demand.sh`
  → `Stubbed: 0 | Skipped: 1576` (0 residuo reale).
- **Audit finale**: `bash bashscripts/tools/audit-agents-on-demand-stubs.sh` →
  `Bloated (non-hot, >40 lines): 0` — `OK: .agents on-demand stubs`.

Lavoro eseguito in combinazione da più agenti concorrenti (script personale
`/tmp/synthesize-rules.php` di questa sessione + tooling ufficiale poi emerso:
`tools/compact-rules-to-wiki-bridges.sh`, `tools/compact-agents-skills-rules-on-demand.sh`,
`tools/audit-agents-on-demand-stubs.sh`). Commit finale in `bashscripts` repo:
`0b1aea5` (merge di `dev`, net -85628 righe su 2977 file).

## Regola di sicurezza applicata

**Mai sovrascrivere contenuto wiki esistente con sostanza reale** — solo se il
target wiki non esiste affatto, o se esiste ma è uno stub vuoto (solo frontmatter,
body vuoto — trovato 5 casi così tra le collisioni, es. `geo-best-practices.md`,
`laravel12-provider-philosophy.md`, entrambi 44 byte, solo frontmatter Cursor senza
body).

## Se stai lavorando su questi file in parallelo

Questo è un lavoro massivo su `bashscripts` (repo separata, `laraxot/bashscripts_fila5`).
Se hai già in corso modifiche a singoli file in `.agents/rules/` o `.agents/skills/`,
committa/pusha prima di questo batch per evitare che il tuo lavoro finisca dentro un
bridge generato automaticamente. Se vedi questo file dopo che il batch è già
committato, verifica `git log -- bashscripts/ai/.agents/rules/<tuo-file>` per
capire se è già stato convertito.

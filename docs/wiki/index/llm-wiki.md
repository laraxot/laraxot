---
title: "LLM Wiki — mappa di lettura del second brain"
type: index
tags: [second-brain, qmd, llm-wiki, index]
created: 2026-07-06
updated: 2026-07-06
related:
  - ../concepts/wiki-sacred-structure-rule.md
  - ../../../bashscripts/ai/wiki/concepts/second-brain-operating-model.md
  - ../../../bashscripts/ai/wiki/concepts/second-brain-search-stack.md
  - ../../../bashscripts/ai/wiki/concepts/second-brain-usage-gaps-and-improvements.md
  - ../../../bashscripts/tools/audit-wiki-junctions.sh
---

# LLM Wiki — mappa di lettura

Questa directory (`docs/wiki/index/`) è l'unica sotto `docs/wiki/` che resta
una **directory reale locale**, non una junction verso `bashscripts/ai/wiki/`
(vedi [wiki-sacred-structure-rule](../concepts/wiki-sacred-structure-rule.md)).
Contiene le mappe/percorsi di lettura del progetto — non conoscenza sintetizzata
(quella vive nelle cartelle junction: `concepts/`, `memories/`, `rules/`, ecc.).

## Come è strutturato il second brain di questo repo

1. **SSoT**: `bashscripts/ai/wiki/` — unica copia reale di `concepts/`,
   `memories/`, `skills/`, `commands/`, `rules/`, `sources/`, `patterns/`,
   `standards/`, `solutions/`, `summaries/`, `audits/`, `themes/`, `modules/`,
   `phpstan/`, `bmad/`, `agents/`, `_templates/`, `second-brain/`.
2. **`docs/wiki/`** — stesse cartelle, ma come **symlink** verso il SSoT.
   Verifica: `bash bashscripts/tools/audit-wiki-junctions.sh`.
3. **Indice di ricerca**: [qmd](https://github.com/) (binario reale in
   `/usr/bin/qmd`, ricerca ibrida BM25 + vettoriale + reranking), collezione
   `wiki` puntata su `bashscripts/ai/wiki/**/*.md` (non su `docs/wiki/` — i
   symlink ci arrivano comunque, ma il path canonico dei risultati è
   `qmd://wiki/...`).
   - `qmd search "<query>"` — full-text BM25, sempre disponibile.
   - `qmd query "<query>"` — ricerca ibrida con expansion + reranking, richiede
     embedding vettoriali generati (`qmd embed`).
   - `qmd status` — stato indice (documenti totali, vettori embedded/pending).
4. **Moduli e temi**: `laravel/Modules/*/docs/wiki/` e `laravel/Themes/*/docs/wiki/`
   sono wiki **locali autonome**, NON junction. Da questa sessione (2026-07-06)
   sono ricercabili anche via qmd tramite la collezione dedicata
   `modules-wiki` (1267+ file), creata con:

   ```bash
   qmd collection add laravel --name modules-wiki --mask "**/docs/wiki/**/*.md"
   ```

   Uso: `qmd search "<query>" -c modules-wiki` (o `qmd query ... -c modules-wiki`
   per la ricerca ibrida, una volta completato l'embedding). La collezione
   root `wiki` resta separata e continua a coprire solo il SSoT
   `bashscripts/ai/wiki/`.
5. **Coordinamento multi-agente**: `docs/chat/` (non wiki, bacheca volatile).
   Leggere `docs/chat/INDEX.md` prima di lavoro non banale condiviso.

## Problemi noti (verificare prima di fidarsi ciecamente della ricerca)

- Il wrapper `bashscripts/docs/llm-wiki-qmd.sh` ha (o ha avuto) un bug di
  `XDG_CACHE_HOME` che lo fa puntare a una cache vuota — vedi
  `docs/chat/second-brain-qmd-cache-bug-2026-07-06.md`. Se il wrapper non
  trova risultati per query ovvie (es. "phpstan"), chiamare `qmd` diretto per
  confermare se è il wrapper o l'indice il problema.
- L'embedding vettoriale è un processo lungo su migliaia di file
  (`qmd embed`, in background) — mentre è in corso, `qmd query`/`qmd vsearch`
  restano parziali; `qmd search` (lessicale) è sempre pienamente disponibile.

## Prima di scrivere nuova conoscenza

- Regola stabile → scrivi in **entrambi**: `memory/feedback_<topic>.md`
  (locale alla sessione) e `bashscripts/ai/wiki/concepts/<topic>.md`
  (persistente, raggiunge tutti gli agenti via `docs/wiki/concepts/` e qmd).
- Dopo aver scritto nuovi `.md`, aggiorna l'indice: `qmd update` (o
  `bashscripts/docs/llm-wiki-qmd.sh update` una volta corretto il wrapper).
- Non creare cartelle reali duplicate sotto `docs/wiki/{concepts,memories,
  skills,commands,rules,...}` — sono junction, scrivere lì scrive
  direttamente nel SSoT.

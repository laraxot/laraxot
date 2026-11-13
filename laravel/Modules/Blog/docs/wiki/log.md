---
title: "log"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "log"
issues: []
discussions: []
status: draft
---

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)

# Blog Wiki Log

## [2026-09-25] phpstan | tree resolver returns a list
- `GetTreeOptions::resolveTreeModels()` discards collection keys with `array_values()` because the caller iterates models as a sequence; this satisfies the `array<int, Category|Menu>` contract without depending on collection keys.

## [2026-04-15] init | wiki bootstrap
- Struttura wiki/log.md inizializzata.
- Layer raw: tutti i file in `docs/` (eccetto `wiki/`).
- Layer wiki: `docs/wiki/` — LLM-maintained, sintesi ad alto riuso.
- Schema: `docs/.schema/WIKI_SCHEMA.md`
- Adozione moduli: `docs/project/llm-wiki-module-adoption.md`

## [2026-10-14] docs | verifica degli ingressi contro il codice

- Riscritti `README.md` e `00-INDEX.md` (indice canonico). Segnati: `pages.md` e `models/transaction-removal.md` (`stale`), `index.md` e `INDEX.md` (`stub`), `00-index.md` (`stale`).
- Verificato: Fixcity non importa Blog (solo `morph_map.php`); lo usano Cms e il tema TwentyOne; 23 rotte Filament `blog/admin`; `app/resources_to_delete/` ha 112 file; `Transaction` esiste ancora; il modulo `Predict` citato non esiste; 1 test (passa).
- Link rotti negli indici (criterio audit): 16 -> 2 (`raw/`, immutabile). Story: `stories/docs-blog-verifica-ingressi.story.md`.

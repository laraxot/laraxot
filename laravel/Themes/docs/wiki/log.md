---
title: "Themes Wiki Log"
type: log
tags: [themes, wiki, second-brain, log]
created: "2026-04-28"
updated: "2026-07-06"
qmd: "themes wiki log second brain module root hygiene"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/39"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
---

## [2026-07-06] module-root hygiene | tema non impattato

- Nuova regola root: `docs/wiki/rules/module-root-cleanup-rules.md` governa le root dei moduli (cartelle lowercase, no `.txt`, solo `README.md` come markdown root).
- Per i temi resta valida la disciplina docs/README e no `.txt` root; nessuna cartella tema e stata modificata in questa passata.

## [2026-07-06] phpstan Modules | nessun impatto sui temi

- PHPStan globale su `Modules` e stato riportato a zero errori senza modificare temi o asset frontoffice.
- Regola: se un errore nasce in un modulo, documentarlo e correggerlo nel modulo owner; non aggiungere workaround nei temi.
- Restano valide le regole FO: Folio/Volt/Laraxot, niente controller frontoffice, niente estensioni Filament dirette.

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)

# Themes Wiki Log

## [2026-06-05] docs | propagazione HackerNoon + harness su tutti i temi

- `index.md`: hub AI harness, Sixteen FO, frontmatter canon
- Frontmatter + «AI / second brain» su `Themes/*/docs/wiki/index.md`
- Canon: [hackernoon-ai-coding-tips-fixcity-map](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [ai-harness-theme-discipline](./concepts/ai-harness-theme-discipline.md)

## [2026-04-28] docs | second brain operativo per i temi

- creato `index.md` per la wiki trasversale dei temi.
- aggiunto `concepts/second-brain-operating-model.md` con protocollo pratico per boundary tema/modulo.
- mantenuto collegamento al concetto esistente `ui-component-body-and-mind.md` come base architetturale.

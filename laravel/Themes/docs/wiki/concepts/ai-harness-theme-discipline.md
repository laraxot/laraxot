---
title: "AI harness — disciplina agenti (tutti i temi)"
type: concept
tags: [themes, ai, harness, second-brain, folio, wcag]
created: 2026-06-05
updated: 2026-06-05
qmd: "themes ai harness agent discipline second brain folio volt wcag design comuni"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/272"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ./second-brain-operating-model.md
  - ../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../Sixteen/docs/wiki/concepts/ai-harness-theme-sixteen.md
  - ../../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md
---

# AI harness — temi Laraxot

Contratto per `laravel/Themes/<Nome>/docs/`.

## Canon

| Argomento | Fonte |
|-----------|--------|
| Tips 001–022 | [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md) |
| Sixteen (FO attivo) | [ai-harness-theme-sixteen.md](../../Sixteen/docs/wiki/concepts/ai-harness-theme-sixteen.md) |
| Frontmatter | [wiki-markdown-frontmatter-mandatory.md](../../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md) |

## Confine tema / modulo

- **Tema**: presentazione, Folio/Volt, CSS, WCAG, asset Vite
- **Modulo**: business logic, JSON/API, Filament admin, migrazioni
- Documentare sempre chi è owner di cosa (Tip 019 — *why*)

## Regole FO critiche

- No Controller frontoffice — Folio + Volt + Filament widget
- `@volt('...')` **statico** a compile-time
- No inline JS — `docs/rules/NO-INLINE-JS.md` (Sixteen)
- Design Comuni class names — no classi feature-prefixed

## Checklist agente (tema)

| Tip | Tema |
|-----|------|
| 001 | Git forward-only; commit utente prima di sessioni lunghe |
| 003 | Piano UX/Folio prima di refactor view |
| 006 | Review WCAG/contrasto su auth e form |
| 009 | QMD mirato — no dump `resources/views/` |
| 015 | No inline JS, Filament-first, `@volt` statico |
| 019 | Documentare confine tema vs modulo owner |
| 020 | `docs/wiki/log.md` tema dopo decisioni FO |

Prompt: [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)

## Issue owner tema

`git remote -v` in `Themes/<T>/` → issue + discussion nel frontmatter di ogni `.md` del tema.

## Collegamenti

- [second-brain-operating-model.md](./second-brain-operating-model.md)
- [Themes wiki index](../index.md)

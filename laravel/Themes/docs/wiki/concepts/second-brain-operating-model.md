---
title: "Second Brain Operating Model (Themes)"
type: concept
tags: [second-brain, themes, ux, parity, llm-wiki]
created: 2026-05-19
updated: 2026-06-05
qmd: "second brain operating model themes ux parity boundary module owner"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/272"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ./ai-harness-theme-discipline.md
  - ../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../Sixteen/docs/wiki/concepts/ai-harness-theme-sixteen.md
---

# Second Brain Operating Model (Themes)

## Obiettivo

Memoria viva per parity UI, confini tema/modulo, anti-regressione FO (Tip 020).

## Contratto tema/modulo

- **Tema**: presentazione, UX, responsive, WCAG, asset
- **Modulo owner**: business logic, state, contratti runtime (map-lit → Geo, ticket → Fixcity)
- Ogni fix tema **linka** la regola modulo correlata

## AI harness

- [ai-harness-theme-discipline.md](./ai-harness-theme-discipline.md) — tutti i temi
- [ai-harness-theme-sixteen.md](../../Sixteen/docs/wiki/concepts/ai-harness-theme-sixteen.md) — FO Fixcity attivo
- [mappa HackerNoon](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md)

## Best practices

- Regola nuova → `index.md` + `log.md` tema
- Documenti brevi, esempi fare/non fare
- Frontmatter con issue/discussion **tema** (`theme_*_fila5`)
- Tip 006: review UX/accessibilità prima di merge

## Bad practices

- Logica business nel tema
- Duplicare policy root
- Volt dinamico con variabili route a compile-time

## Collegamenti

- [Themes wiki index](../index.md)
- [frontmatter obbligatorio](../../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md)

---
title: "Theme Zero Wiki — Schema e Convenzioni"
type: guide
tags: ['laravel']
created: 2026-07-14
updated: 2026-07-14
qmd: "theme zero wiki schema e convenzioni"
related:
  - "./schema.md"
  - "./concepts/bmad-method.md"
  - "./log.md"
id: zero-docs-wiki-schema
slug: schema
description: "Tema Zero per la piattaforma PTVX. Tema base/default con layout, stili e componenti Blade per l'interfaccia pubblica e amministrativa."
document_type: guide
category: database
status: active
issues: []
discussions: []
---

# Theme Zero Wiki — Schema e Convenzioni

## Dominio
Tema Zero per la piattaforma PTVX. Tema base/default con layout, stili e componenti Blade per l'interfaccia pubblica e amministrativa.

## Directory Standard

| Directory | Purpose |
|-----------|---------|
| `commands/` | Command documentation and CLI entry points |
| `concepts/` | Concept explanations and architectural overviews |
| `decisions/` | Architectural decision records (ADRs) |
| `glossary/` | Term definitions and glossary entries |
| `how-to/` | Step-by-step how-to guides |
| `memories/` | Memory entries and historical notes |
| `overviews/` | Overview documents and summaries |
| `queries/` | Scratchpad and query entries |
| `reference/` | Reference documentation |
| `rules/` | Rules, guidelines, and constraints |
| `skills/` | Skill definitions and reusable AI skills |
| `summaries/` | Summary and report documents |
| `entities/` | Entity documentation (classes, patterns) |
| `lint/` | Lint results and quality checks |
| `comparisons/` | Comparison documents (A vs B) |

## Directory di supporto (non-standard)

| Directory | Purpose |
|-----------|---------|
| `screenshots/` | Image assets and visual documentation |
| `raw/` | Raw source documents awaiting ingestion |
| `sources/` | Source summaries and external references |

## Tipi di Entità
- **Class**: Classi PHP, traits, interfacce specifiche del modulo
- **Pattern**: Pattern architetturali usati nel modulo
- **Rule**: Vincoli rigidi che non devono mai essere violati
- **Decision**: Decisioni architetturali con relativa motivazione

## Entità Principali
- ZeroThemeServiceProvider: Provider del tema
- ZeroLayout: Layout principale
- ZeroComponent: Componenti Blade specifici

## Pattern Rilevanti
- Theme Pattern: override view Laravel
- Component Pattern: componenti Blade riutilizzabili

## Protocollo di Ingest
1. Leggere il documento sorgente raw
2. Estrarre entità (classi, pattern, regole, decisioni)
3. Scrivere/aggiornare pagine entità in `entities/`
4. Scrivere/aggiornare pagine concetti in `concepts/`
5. Aggiungere un riassunto in `sources/`
6. Aggiornare il catalogo `index.md`
7. Appendere a `log.md`

## Convenzione Nomi File
- `concepts/{kebab-case}.md`
- `entities/{ClassName}.md`
- `comparisons/{a}-vs-{b}.md`
- `sources/{source-filename}.md`
- Tutti i nomi file seguono `{lowercase-kebab-case}.md`

## Regola Cross-linking
Ogni pagina DEVE linkare almeno un'altra pagina wiki.
Le pagine orfane sono un errore di lint.

## Standard di Qualità
- Nessuna claim obsoleta oltre 30 giorni senza ri-verifica
- Ogni pagina entità deve riferirsi al doc sorgente raw
- Le contraddizioni tra pagine devono essere risolte immediatamente
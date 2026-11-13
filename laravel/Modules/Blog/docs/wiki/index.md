---
title: "Blog Module Wiki Index"
type: index
module: Blog
tags: [blog, wiki, index]
created: 2026-04-15
updated: 2026-06-05
qmd: "blog module wiki index second brain harness"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/272"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../../../../docs/wiki/bmad/architecture.md
  - ../../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md
  - ../../docs/wiki/concepts/ai-harness-module-discipline.md
status: draft
---

# Blog Module LLM Wiki

## AI / second brain

- [hackernoon-ai-coding-tips-fixcity-map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md)
- [bmad/architecture](../../../../../docs/wiki/bmad/architecture.md)
- [frontmatter + GitHub](../../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md)
- ai-harness-module-discipline _(riferimento non presente nel repository, verificato 2026-10-14)_
- [second-brain-local-discipline](./concepts/second-brain-local-discipline.md) → canon Xot


Indice operativo del wiki Blog.

## Struttura canonica (sacra)

- [concepts/](./concepts/): Pattern architetturali e metodologie.
- [entities/](./entities/): Modelli e componenti chiave.
- sources/ _(riferimento non presente nel repository, verificato 2026-10-14)_: Dati di ricerca e link esterni.
- [comparisons/](./comparisons/): Implementazioni alternative.
- [decisions/](./decisions/): ADL (Architectural Decision Log).
- [troubleshooting/](./troubleshooting/): Problemi noti e soluzioni.
- _archive/ _(riferimento non presente nel repository, verificato 2026-10-14)_: Documentazione legacy.
- [_templates/](./_templates/): Template standard.

## Regole collegate

- [forbidden-folders-rule](../../../../../docs/wiki/concepts/forbidden-folders.md): Vincoli strutturali strict.
- [llm-wiki-standard](../../../../../docs/project/karpathy-llm-wiki-adoption.md): Mapping repository e ciclo di vita conoscenza.
- cms-driven-pages _(riferimento non presente nel repository, verificato 2026-10-14)_: Pagine CMS per blog.

## Scopo Blog Module

Gestione articoli, categorie, tag e contenuti editoriali con supporto CMS.

## Compiled Pages

| Pagina | Tipo | Argomento | Data |
|--------|------|-----------|------|
| [.gitkeep](./concepts/.gitkeep) | Concept | - | 2026-04-21 |

## Best Practices

- Usare CMS-driven pages con JSON config (vedi cms-driven-pages _(riferimento non presente nel repository, verificato 2026-10-14)_)
- Implementare `casts()` method non `$casts` property (vedi model-casts-phpstan _(riferimento non presente nel repository, verificato 2026-10-14)_)
- Usare Folio routing per pagine blog (vedi folio-routing _(riferimento non presente nel repository, verificato 2026-10-14)_)

## Bad Practices

- NON creare Service classes - usare Actions (vedi [actions-over-services-governance](https://github.com/laraxot/base_fixcity_fila5/blob/main/.opencode/skills/actions-over-services-governance/SKILL.md))
- NON usare `dehydrated(false)` nei trait - blocca salvataggio (vedi Geo CoordinatePicker fix)
- NON duplicare slug logic - usare trait dedicati (vedi slug-unique-rule _(riferimento non presente nel repository, verificato 2026-10-14)_)

## False Friends

- `live()` in Filament non rende il campo sempre live - serve `$applyStateBindingModifiers()` (vedi [coordinate-picker-state-binding-rule](../../../Geo/docs/wiki/concepts/coordinate-picker-state-binding-rule.md))
- `dehydrated(false)` sembra mantenere il campo nei dati ma blocca il salvataggio

## Troubleshooting

| Pagina | Tipo | Argomento |
|--------|------|-----------|
| [.gitkeep](./concepts/.gitkeep) | Concept | Template iniziale |

Aggiornato: 2026-04-28

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
  - ../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../../../docs/wiki/bmad/architecture.md
  - ../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md
  - ../../docs/wiki/concepts/ai-harness-module-discipline.md
---

# Blog Module LLM Wiki

## AI / second brain

- [hackernoon-ai-coding-tips-fixcity-map](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md)
- [bmad/architecture](../../../../docs/wiki/bmad/architecture.md)
- [frontmatter + GitHub](../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md)
- [ai-harness-module-discipline](../../docs/wiki/concepts/ai-harness-module-discipline.md)
- [second-brain-local-discipline](./concepts/second-brain-local-discipline.md) → canon Xot


Indice operativo del wiki Blog.

## Struttura canonica (sacra)

- [concepts/](./concepts/): Pattern architetturali e metodologie.
- [entities/](./entities/): Modelli e componenti chiave.
- [sources/](./sources/): Dati di ricerca e link esterni.
- [comparisons/](./comparisons/): Implementazioni alternative.
- [decisions/](./decisions/): ADL (Architectural Decision Log).
- [troubleshooting/](./troubleshooting/): Problemi noti e soluzioni.
- [_archive/](./_archive/): Documentazione legacy.
- [_templates/](./_templates/): Template standard.

## Regole collegate

- [forbidden-folders-rule](../../../../docs/wiki/concepts/forbidden-folders.md): Vincoli strutturali strict.
- [llm-wiki-standard](../../../../docs/project/karpathy-llm-wiki-adoption.md): Mapping repository e ciclo di vita conoscenza.
- [cms-driven-pages](../../../../docs/wiki/concepts/cms-driven-pages.md): Pagine CMS per blog.

## Scopo Blog Module

Gestione articoli, categorie, tag e contenuti editoriali con supporto CMS.

## Compiled Pages

| Pagina | Tipo | Argomento | Data |
|--------|------|-----------|------|
| [.gitkeep](./concepts/.gitkeep) | Concept | - | 2026-04-21 |

## Best Practices

- Usare CMS-driven pages con JSON config (vedi [cms-driven-pages](../../../../docs/wiki/concepts/cms-driven-pages.md))
- Implementare `casts()` method non `$casts` property (vedi [model-casts-phpstan](../../../../docs/wiki/concepts/model-casts-phpstan.md))
- Usare Folio routing per pagine blog (vedi [folio-routing](../../../../docs/wiki/concepts/folio-routing.md))

## Bad Practices

- NON creare Service classes - usare Actions (vedi [actions-over-services-governance](https://github.com/laraxot/base_fixcity_fila5/blob/main/.opencode/skills/actions-over-services-governance/SKILL.md))
- NON usare `dehydrated(false)` nei trait - blocca salvataggio (vedi Geo CoordinatePicker fix)
- NON duplicare slug logic - usare trait dedicati (vedi [slug-unique-rule](../../../../docs/wiki/concepts/slug-unique-rule.md))

## False Friends

- `live()` in Filament non rende il campo sempre live - serve `$applyStateBindingModifiers()` (vedi [coordinate-picker-state-binding-rule](../../Geo/docs/wiki/concepts/coordinate-picker-state-binding-rule.md))
- `dehydrated(false)` sembra mantenere il campo nei dati ma blocca il salvataggio

## Troubleshooting

| Pagina | Tipo | Argomento |
|--------|------|-----------|
| [.gitkeep](./concepts/.gitkeep) | Concept | Template iniziale |

Aggiornato: 2026-04-28

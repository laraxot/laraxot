---
title: "Comment Module Wiki Index"
type: index
module: Comment
tags: [comment, wiki, index]
created: 2026-04-15
updated: 2026-06-06
qmd: "comment module wiki index second brain harness native comments spatie internalization"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/4"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/297"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/297#issuecomment-4633157264"
related:
  - ../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../../../docs/wiki/bmad/architecture.md
  - ../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md
  - ../../docs/wiki/concepts/ai-harness-module-discipline.md
---

# Comment Module LLM Wiki

## AI / second brain

- [hackernoon-ai-coding-tips-fixcity-map](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md)
- [bmad/architecture](../../../../docs/wiki/bmad/architecture.md)
- [frontmatter + GitHub](../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md)
- [ai-harness-module-discipline](../../docs/wiki/concepts/ai-harness-module-discipline.md)
- [second-brain-local-discipline](./concepts/second-brain-local-discipline.md) → canon Xot


Indice operativo del wiki Comment.

## Native engine (STORY-158)

- [module-providers-manifest](./concepts/module-providers-manifest.md) — **no** `$this->app->register()` nel provider padre
- [native-comments-engine-workflow](./concepts/native-comments-engine-workflow.md) — workflow BMAD 4 fasi
- [architecture/native-comments-engine](../architecture/native-comments-engine.md)
- [spatie-to-laraxot-namespace-map](./concepts/spatie-to-laraxot-namespace-map.md)
- Script: `bashscripts/tools/comment/audit-spatie-usage.sh`

## Integrazioni FO

- [native-comments-architecture](./concepts/native-comments-architecture.md) — **STORY-158** architettura target (no Spatie vendor)
- [spatie-package-inventory](./concepts/spatie-package-inventory.md) — mapping migrazione
- [adr-internalize-spatie-comments](./decisions/adr-internalize-spatie-comments.md)
- [spatie-comments-fo-ticket-integration](./concepts/spatie-comments-fo-ticket-integration.md) — stato attuale FO ticket
- Fixcity: [ticket-view-fo-enrichment](../Fixcity/docs/wiki/concepts/ticket-view-fo-enrichment-map-media-comments.md)

## Struttura canonica (sacred)

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

## Scopo Comment Module

Gestione commenti con moderazione, nesting, e relazioni polimorfiche.

## Compiled Pages

| Pagina | Tipo | Argomento | Data |
|--------|------|-----------|------|
| [.gitkeep](./concepts/.gitkeep) | Concept | - | 2026-04-21 |

## Best Practices

- Usare relazioni polimorfiche Eloquent per commenti (vedi [eloquent-best-practices](../../../../docs/wiki/concepts/eloquent-best-practices.md))
- Implementare `casts()` method non `$casts` property (vedi [model-casts-phpstan](../../../../docs/wiki/concepts/model-casts-phpstan.md))
- Usare Actions per moderazione (vedi [actions-over-services-governance](https://github.com/laraxot/base_fixcity_fila5/blob/main/.opencode/skills/actions-over-services-governance/SKILL.md))

## Bad Practices

- NON creare Service classes - usare Actions (vedi [actions-over-services-governance](https://github.com/laraxot/base_fixcity_fila5/blob/main/.opencode/skills/actions-over-services-governance/SKILL.md))
- NON usare `dehydrated(false)` nei trait - blocca salvataggio (vedi Geo CoordinatePicker fix)
- NON hardcodare stati moderazione - usare Enums (vedi [laravel-enums](../../../../docs/wiki/concepts/laravel-enums.md))

## False Friends

- `dehydrated(false)` sembra mantenere il campo nei dati ma blocca il salvataggio (vedi [coordinate-picker-filament5-save-pattern](../../Geo/docs/wiki/concepts/coordinate-picker-filament5-save-pattern.md))
- `live()` in Filament non rende il campo sempre live - serve `$applyStateBindingModifiers()` (vedi [coordinate-picker-state-binding-rule](../../Geo/docs/wiki/concepts/coordinate-picker-state-binding-rule.md))

## Troubleshooting

| Pagina | Tipo | Argomento |
|--------|------|-----------|
| [.gitkeep](./concepts/.gitkeep) | Concept | Template iniziale |

Aggiornato: 2026-04-28

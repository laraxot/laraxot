---
title: "Gestionale — indice docs Themes"
type: index
tags: [themes, gestionale-commesse, folio, filament, bmad]
created: 2026-07-24
updated: 2026-07-24
qmd: "themes gestionale docs index panels boundaries wave1 operator matrix"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./gc-modules-runtime-matrix.md"
  - "./module-vs-theme-boundaries.md"
  - "./wave1-fo-operator-note.md"
  - "./gestionale-panels-vs-themes.md"
  - "./new-modules-panel-mapping.md"
  - "./aiassistant-vs-themes.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "../../docs/migrate-gestionale-tests.md"
  - "../../../docs/stories/2.5.migrate-gestionale-tests.story.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
---

# Gestionale — indice docs Themes

> **Perché:** Themes ≠ BL. Runtime enable = [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md). Gate field-service: [functional-readiness.md](../../../docs/gestionale-commesse-comparison/functional-readiness.md).

| Doc | Scopo |
|---|---|
| [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md) | **SSoT** `modules_statuses` + rischi (Document/media) |
| [module-vs-theme-boundaries.md](./module-vs-theme-boundaries.md) | Modules vs Themes vs app |
| [wave1-fo-operator-note.md](./wave1-fo-operator-note.md) | Operator resta Filament in Wave 1 |
| [gestionale-panels-vs-themes.md](./gestionale-panels-vs-themes.md) | Panel SRC vs tema |
| [new-modules-panel-mapping.md](./new-modules-panel-mapping.md) | URL `*/admin` |
| [aiassistant-vs-themes.md](./aiassistant-vs-themes.md) | AiAssistant non va nei temi |
| [copy-normalize-modules-vs-themes.md](./copy-normalize-modules-vs-themes.md) | Copy/normalize non tocca Themes composer |
| [composer-modules-not-themes.md](./composer-modules-not-themes.md) | Merge-plugin solo moduli |

## Test FO vs moduli

Operator / panel FO restano Filament nei moduli (non Themes). Copertura Pest: story [2.5](../../../docs/stories/2.5.migrate-gestionale-tests.story.md) + playbook [migrate-gestionale-tests.md](../../docs/migrate-gestionale-tests.md). Catalog/HR green sul fixture sqlite; Document Feature bloccati da D1 media.

## Tema attivo FO

Skin pubblica: [Sixteen/docs/README.md](../Sixteen/docs/README.md). Non spostare CRUD field-service nelle pages Folio finché story FO dedicata.

Stub gestionale (pointer al hub) anche in: [Zero](../Zero/docs/gestionale-panels-vs-themes.md) · [TwentyOne](../TwentyOne/docs/gestionale-panels-vs-themes.md) · [Meetup](../Meetup/docs/gestionale-panels-vs-themes.md) · [Barthelemy](../Barthelemy/docs/gestionale-panels-vs-themes.md).

## BMAD

Story [2.3](../../../docs/stories/2.3.deep-quality-enabled-modules.story.md) · [2.4](../../../docs/stories/2.4.module-theme-docs-hygiene.story.md) · Issue [#14](https://github.com/laraxot/base_workorder_fila5/issues/14) · Discussion [#15](https://github.com/laraxot/base_workorder_fila5/discussions/15)

## Test

I test field-service non stanno nei Themes: [migrate-gestionale-tests.md](../../docs/migrate-gestionale-tests.md).

---
id: zero-docs-bmad-readme
slug: readme
title: "Mappa della cartella bmad del tema Zero"
description: "Raggruppa per tema le guide, i report e la pianificazione in docs/bmad, indica i canonici dei duplicati e il caso del gemello readme.md."
document_type: index
type: index
category: documentation
status: active
tags: [zero, bmad, indice]
created: "2026-10-06"
updated: "2026-10-08"
qmd: "bmad tema zero mappa cartella guide report pianificazione duplicati canonico readme gemello"
issues: []
discussions: []
related:
  - ./00-index.md
  - ../README.md
---

# Mappa della cartella bmad del tema Zero

`docs/bmad/` e' il contenitore storico piu' grande della docs del tema Zero: guide, report, regole e pianificazione, quasi tutti piatti. Il punto di ingresso della docs e' [../README.md](../README.md); l'indice storico di questa cartella e' [00-index.md](./00-index.md).

## Gemello readme.md

Esistono due file che differiscono solo per la maiuscola: questo `README.md` (mappa) e [readme.md](./readme.md) (panoramica lunga del tema, 27 KB). Su filesystem case-insensitive collidono. Non si rinomina senza una decisione: la proposta e' spostare il contenuto di `readme.md` in `theme-overview.md` e aggiornare i riferimenti.

## Gruppi

| Gruppo | Documenti principali |
|---|---|
| Architettura e confini | [architecture](./architecture.md), [modern-theme-architecture](./modern-theme-architecture.md), [theme-architecture-best-practices](./theme-architecture-best-practices.md), [accessor-delegation-pattern](./accessor-delegation-pattern.md), [composer-modules-not-themes](./composer-modules-not-themes.md), [model-usage-in-themes](./model-usage-in-themes.md) |
| Viste e UI | [layouts](./layouts.md), [components](./components.md), [customization](./customization.md), [mail-layouts](./mail-layouts.md), [authentication](./authentication.md), [navigation-integration](./navigation-integration.md) |
| Filament e grafici | [filament-chart-integration](./filament-chart-integration.md), [filament-table-architecture](./filament-table-architecture.md), [jpgraph-integration-guide](./jpgraph-integration-guide.md), famiglia `chartjs-datalabels-*` |
| Qualita' e PHPStan | [phpstan](./phpstan.md), [phpstan-dry-kiss-theme-guidelines](./phpstan-dry-kiss-theme-guidelines.md), [php-quality-gates-rule](./php-quality-gates-rule.md), [quality-audit](./quality-audit.md), [no-phpstan-probe-policy](./no-phpstan-probe-policy.md) |
| Git e sync | [conflict-resolution](./conflict-resolution.md), [conflict-resolution-summary](./conflict-resolution-summary.md), [git-collision-audit-bashscripts](./git-collision-audit-bashscripts.md), [multi-org-sync-laraxot-provtv](./multi-org-sync-laraxot-provtv.md), [no-git-lfs](./no-git-lfs.md) |
| Prodotto e pianificazione | [prd](./prd.md), [product-requirements](./product-requirements.md), [product-roadmap](./product-roadmap.md), [product-strategy](./product-strategy.md), [sprint-planning](./sprint-planning.md), [tech-spec](./tech-spec.md), [epics](./epics/zero-epics-and-stories.md) |
| Governance della docs | [docs-archive-policy](./docs-archive-policy.md), [docs-deduplication](./docs-deduplication.md), [model-docs-governance](./model-docs-governance.md), [naming-conventions](./naming-conventions.md), [theme-documentation-standard](./theme-documentation-standard.md) |
| Story | [bmad/stories](./stories/) |

## Duplicati e canonici

| Argomento | Canonico | Copia (stato) |
|---|---|---|
| Changelog | [../changelog.md](../changelog.md) | [changelog.md](./changelog.md) superseded |
| Guida JpGraph | [../wiki/concepts/jpgraph-guide.md](../wiki/concepts/jpgraph-guide.md) | [jpgraph-guide.md](./jpgraph-guide.md) superseded |
| Risorse annidate Filament 5 | [../wiki/concepts/filament-nested-resources.md](../wiki/concepts/filament-nested-resources.md) | [filament-5-nested-resources.md](./filament-5-nested-resources.md) superseded |
| Audit collisioni Git in bashscripts | [git-collision-audit-bashscripts.md](./git-collision-audit-bashscripts.md) | [git-collisions-bashscripts-audit.md](./git-collisions-bashscripts-audit.md), [git-conflict-resolution-audit.md](./git-conflict-resolution-audit.md) superseded |

Nomi vicini non sono duplicati: `launch-plan`, `roadmap` e `strategy` hanno 5-7 righe di corpo, le versioni `product-*` ne hanno 300-450, e non condividono testo. Vanno lette entrambe finche' non c'e' una decisione su quale tenere. Tra le copie vere restano solo quelle in tabella e quelle in `_archive/`.

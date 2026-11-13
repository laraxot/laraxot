---
id: zero-docs-bmad-readme-2
slug: readme
title: "Tema Zero - Documentazione"
description: "Tema alternativo/sperimentale. Hub: gestionale-docs-index.md · tenant-modules-navigation-discipline.md · panels vs Zero."
document_type: index
type: index
category: documentation
status: active
tags: []
created: "2026-10-06"
updated: "2026-10-08"
qmd: "tema zero - documentazione"
issues: []
discussions: []
---

# Tema Zero - Documentazione

## Gestionale / replica

Tema alternativo/sperimentale. Hub: [gestionale-docs-index.md](../../docs/gestionale-docs-index.md) · [tenant-modules-navigation-discipline.md](../../docs/tenant-modules-navigation-discipline.md) · [panels vs Zero](./gestionale-panels-vs-themes.md).

## Overview

Il tema **Zero** è il tema principale di default per l'applicazione Laraxot PTVX.

## Scopo (business)

- **Frontoffice**: layout e pagine base, con convenzioni condivise.
- **Coerenza**: integrazione con `UI` per componenti, e con `Xot` per regole architetturali.

## Struttura

```
Zero/
├── app/
│   ├── Http/
│   ├── View/
│   └── ...
├── config/
├── docs/
├── lang/
├── resources/
│   ├── views/
│   └── svg/
└── routes/
```

## Configurazione

### Regole Fondamentali

1. **PHPStan**: Configurazione centralizzata in `laravel/phpstan.neon`
2. **Output files**: `phpstan*.json` ignorati (NON committare)
3. **Namespace**: `Themes\Zero\`

## Repo indipendente

Path in `gitmodules.ini`: `laravel/Themes/Zero` → remote `laraxot/theme_zero_fila5`. Entrare con `cd`, non trattarlo come submodule della root. Protocollo: [17-gitmodules-path-iteration.md](../../../../bashscripts/tools/prompts/17-gitmodules-path-iteration.md).

## Collegamenti

- [PHPStan Docs](./phpstan.md)
- [Configurazione Root](../../../docs/THEME_ZERO.md)
- [Metodologia GSD](../../../../docs/project/gsd-methodology.md)
- [GSD templates locali](../../../../.gsd/README.md)

## Backlinks

- [Xot Module](../../Modules/Xot/docs/)
- [UI Module](../../Modules/UI/docs/)

## AI Workflows
- [AI Methodologies](../wiki/how-to/ai-methodologies.md)

 <!-- swarm-docs:index:start -->

> **Audit completato 2026-10-06**: tutti i duplicati sono stati risolti.
> - 24 file duplicati (redirect/stub) eliminati
> - 12 file con naming convention non standard (maiuscole/underscore) rimossi in favore delle versioni kebab-case
> - 5 file con contenuto identico (exact dup) eliminati
> - File `readme.md`/`changelog.md` (kebab-case) sono le versioni canoniche
> - Merge conflict: risolti in 00-index.md, conflict-resolution-summary.md, dry-kiss-best-practices.md,
  duplicate-methods.md, duplicate-methods-report.md, metodi-duplicati-analisi.md,
  product-launch-plan.md, product-roadmap.md, product-strategy.md, sprint-planning.md,
  simplechartwidget-quality-analysis.md

## Mappa della documentazione (indice di radice, generato dalla passata swarm-docs 2026-10-06)

Sezione generata: raggruppamento euristico per nome e titolo. **Aggiornato post-deduplication**:
i file duplicati sono stati rimossi; le segnalazioni `[dup]` e `[deprecated]` sono state risolte.

### Entry point e struttura

- Modulo o tema: [../readme.md](../readme.md) (vetrina), [../../../Modules/Xot/docs/README.md](../../../Modules/Xot/docs/README.md) (docs del modulo base Xot)
- [00-index.md](./00-index.md): indice canonico (00-INDEX.md rimosso — duplicate)
- [purpose.md](./purpose.md): scopo (esiste anche l'equivalente italiano/inglese, possibile duplicato)
- [scopo.md](./scopo.md): scopo (esiste anche l'equivalente italiano/inglese, possibile duplicato)
- Architettura: [architecture.md](./architecture.md)
- Architettura: [architecture-rules.md](../wiki/rules/architecture-rules.md)
- [wiki/index.md](./wiki/index.md)
- Story BMAD (posizione canonica): [bmad/stories/](./bmad/stories/) (2 file)

### Sottocartelle

| Cartella | File .md (ricorsivo) | Entry point | Nota |
| --- | --- | --- | --- |
| [_archive/](./_archive/) | 19 | [index.md](./_archive/index.md) | archivio, non aggiungere file |
| [archive/](./archive/) | 1 | nessuno | archivio, non aggiungere file |
| [bmad/](./bmad/) | 2 | nessuno |  |
| [concepts/](./concepts/) | 1 | nessuno |  |
| [epics/](./epics/) | 1 | nessuno |  |
| [journals/](./journals/) | 0 | nessuno |  |
| [mysql-remote/](./mysql-remote/) | 1 | [README.md](./mysql-remote/README.md) |  |
| [outputs/](./outputs/) | 1 | [README.md](./outputs/README.md) |  |
| [prompts/](./prompts/) | 1 | nessuno |  |
| [raw/](./raw/) | 1 | [README.md](./raw/README.md) | materiale grezzo |
| [roadmap/](./roadmap/) | 6 | nessuno |  |
| [root-md-files/](./root-md-files/) | 3 | nessuno | archivio, non aggiungere file |
| [schema/](./schema/) | 0 | nessuno |  |
| [screenshots/](./screenshots/) | 2 | nessuno |  |
| [skills/](./skills/) | 1 | [README.md](./skills/README.md) |  |
| [stories/](./stories/) | 4 | nessuno | legacy: la posizione canonica e' `bmad/stories/` |
| [wiki/](./wiki/) | 39 | [INDEX.md](./wiki/INDEX.md), [index.md](./wiki/index.md) |  |

### Sovrapposizioni rilevate (nessuna azione eseguita)

- `stories/` (4 file) e `bmad/stories/`: 0 stessi nomi, 0 byte-identici. La posizione canonica e' `bmad/stories/`.

### File di radice per argomento

#### Agenti AI e regole di lavoro (12)

- [agent-confidence-discipline.md](../wiki/rules/agent-confidence-discipline.md): Disciplina agenti per massimizzare la confidenza
- [agent-confidence-protocol.md](../wiki/rules/agent-confidence-protocol.md): Massima confidenza agente [marker di merge]
- [agent-edit-discipline.md](../wiki/rules/agent-edit-discipline.md): agent edit discipline — puntatore [marker di merge]
- [ai-development-guide.md](./ai-development-guide.md): AI-Assisted Development Guide - Zero Theme [marker di merge]
- [ai-handoff.md](../wiki/concepts/ai-handoff.md): ai handoff [marker di merge]
- [ai-methodologies.md](../wiki/how-to/ai-methodologies.md): AI Methodologies Handbook [marker di merge]
- [ai-tooling.md](../wiki/concepts/ai-tooling.md): Strumenti AI nel tema Zero
- [doc-first-workflow.md](./doc-first-workflow.md): Theme Zero - Doc-First Workflow [marker di merge]
- [docs-confidence-audit.md](./docs-confidence-audit.md): Zero Theme Docs Confidence Audit - 2026-03-07 [marker di merge]
- [graphify-map.md](./graphify-map.md): Zero Theme — Mappa Graphify [marker di merge]
- [no-ai-tool-scaffold-dirs.md](./no-ai-tool-scaffold-dirs.md): No AI/tool scaffold directories in theme tree
- [second-brain.md](./second-brain.md): second brain — puntatore modulo [marker di merge]

#### PHPStan, qualita e test (31)

- [METODI-DUPLICATI-ANALISI.md](./metodi-duplicati-analisi.md): Metodi Duplicati — Analisi Tema Zero [dup]
- [METODI_DUPLICATI_ANALISI.md](./metodi-duplicati-analisi.md): METODI-DUPLICATI-ANALISI (deprecated) [dup] [marker di merge]
- [code-quality-improvement-report.md](./code-quality-improvement-report.md): Code Quality Improvement Report — Zero [marker di merge]
- [code-quality-improvements.md](./code-quality-improvements.md): Code Quality Improvements - Zero Theme [marker di merge]
- [code-quality-report.md](./code-quality-report.md): Code quality — tema Zero [marker di merge]
- [code-redundancy-audit.md](./code-redundancy-audit.md): Code redundancy audit — Zero [marker di merge]
- [dry-kiss-analysis.md](./dry-kiss-analysis.md): 🎨 DRY & KISS Analysis - Theme Zero [marker di merge]
- [dry-kiss-best-practices-historic.md](./dry-kiss-best-practices-historic.md): DRY & KISS Best Practices - Tema Zero [dup] [marker di merge]
- [dry-kiss-best-practices.md](./dry-kiss-best-practices.md): DRY & KISS Best Practices - Tema Zero [dup] [marker di merge]
- [duplicate-methods-report.md](./duplicate-methods-report.md): Report: Metodi con nome duplicato nei moduli e nei temi [dup] [marker di merge]
- [duplicate-methods.md](./duplicate-methods.md): Metodi duplicati — Zero [dup] [marker di merge]
- [duplicate_methods.md](./duplicate-methods.md): duplicate-methods (deprecated) [dup]
- [duplicate_methods_report.md](./duplicate-methods-report.md): duplicate-methods-report (deprecated) [dup]
- [git-collision-audit-bashscripts.md](./git-collision-audit-bashscripts.md): Audit collisioni Git committate in bashscripts [dup]
- [git-collisions-bashscripts-audit.md](./git-collisions-bashscripts-audit.md): Audit collisioni Git committate in bashscripts [dup]
- [ide-helper-phpdoc-boundary.md](./ide-helper-phpdoc-boundary.md): ide helper — confine PHPDoc tema Zero [marker di merge]
- [metodi-duplicati-analisi.md](./metodi-duplicati-analisi.md): Metodi Duplicati — Analisi Tema Zero [dup] [marker di merge]
- [no-phpstan-probe-policy.md](./no-phpstan-probe-policy.md): No PHPStan probe files in themes
- [php-quality-gates-rule.md](./php-quality-gates-rule.md): Theme Zero - PHP Quality Gates Rule [marker di merge]
- [phpstan-compliance-status.md](./phpstan-compliance-status.md): PHPStan Level 10 Compliance Status [marker di merge]
- [phpstan-dry-kiss-guidelines.md](./phpstan-dry-kiss-guidelines.md): PHPStan Level 10 + DRY/KISS Guidelines for Themes [dup] [marker di merge]
- [phpstan-dry-kiss-theme-guidelines-historic.md](./phpstan-dry-kiss-theme-guidelines-historic.md): PHPStan Level 10 + DRY/KISS Guidelines for Themes [dup] [marker di merge]
- [phpstan-dry-kiss-theme-guidelines.md](./phpstan-dry-kiss-theme-guidelines.md): PHPStan Level 10 + DRY/KISS Guidelines for Themes [dup] [marker di merge]
- [phpstan-level10-analysis.md](./phpstan-level10-analysis.md): Analisi PHPStan livello 10 - tema [marker di merge]
- [phpstan-level10-theme-compliance.md](./phpstan-level10-theme-compliance.md): PHPStan Level 10 Compliance - Theme System [marker di merge]
- [phpstan-merge-conflicts.md](./phpstan-merge-conflicts.md): Documentation [dup] [marker di merge]
- [phpstan.md](./phpstan.md): PHPStan — Theme Zero [marker di merge]
- [quality-audit.md](./quality-audit.md): Audit di qualita: tema Zero [marker di merge]
- [quality-roadmap.md](./quality-roadmap.md): Quality roadmap — Zero
- [simplechartwidget-quality-analysis.md](./simplechartwidget-quality-analysis.md): SimpleChartWidget - Analisi Qualità del Codice e Best Practices [dup] [marker di merge]
- [theme-architecture-best-practices.md](./theme-architecture-best-practices.md): Theme Architecture and Best Practices [marker di merge]

#### Git, sync e conflitti (9)

- [CONFLICT-RESOLUTION-SUMMARY.md](./conflict-resolution-summary.md): CONFLICT-RESOLUTION-SUMMARY (deprecated) [dup] [marker di merge]
- [CONFLICT_RESOLUTION_SUMMARY.md](./conflict-resolution-summary.md): CONFLICT-RESOLUTION-SUMMARY (deprecated) [dup]
- [conflict-resolution-summary.md](./conflict-resolution-summary.md): Riepilogo Risoluzione Conflitti Git - Filament 5 [dup] [marker di merge]
- [conflict-resolution.md](./conflict-resolution.md): Conflict Resolution — Theme Zero [marker di merge]
- [conflict_resolution_summary.md](./conflict-resolution-summary.md): CONFLICT-RESOLUTION-SUMMARY (deprecated) [dup]
- [git-conflict-resolution-audit.md](./git-conflict-resolution-audit.md): Audit collisioni Git committate in bashscripts [dup]
- [git-multi-org-sync-handoff.md](./git-multi-org-sync-handoff.md): Handoff multi-org sync (STORY-003) [marker di merge]
- [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md): Sincronizzazione multi-organizzazione (laraxot + provtv)
- [no-git-lfs.md](./no-git-lfs.md): Git LFS vietato: linea guida e prototipo .gitattributes [marker di merge]

#### Bug fix e troubleshooting (3)

- [performance-calcolo-quota-troubleshooting.md](./performance-calcolo-quota-troubleshooting.md): Troubleshooting Calcolo Quota Performance [marker di merge]
- [simplechartwidget-problems-analysis.md](./simplechartwidget-problems-analysis.md): SimpleChartWidget - Analisi Problemi e Miglioramenti UI/UX [marker di merge]
- [troubleshooting.md](./troubleshooting.md): Zero Theme Troubleshooting Guide [marker di merge]

#### Architettura e pattern (10)

- [ARCHITECTURE.md](./architecture.md): Zero Theme Architecture [dup]
- [accessor-delegation-pattern.md](./accessor-delegation-pattern.md): 🧘 Accessor Delegation Pattern - Zero Theme [marker di merge]
- [architecture-rules.md](../wiki/rules/architecture-rules.md): architecture rules — Theme Zero [marker di merge]
- [architecture.md](./architecture.md): Zero Theme Architecture [dup]
- [filament-infolist-pattern.md](./filament-infolist-pattern.md): Pattern Infolist Filament (Theme Zero) [marker di merge]
- [filament-table-architecture.md](./filament-table-architecture.md): Dove si configura la tabella di una Resource Filament
- [folio-pages-structure.md](./folio-pages-structure.md): Folio pages — struttura tema Zero (ptvx) [marker di merge]
- [modern-theme-architecture.md](./modern-theme-architecture.md): Architettura Moderna dei Temi - Zero Theme [marker di merge]
- [performance-actions-reference.md](./performance-actions-reference.md): Performance actions reference [marker di merge]
- [philosophy.md](./philosophy.md): Zero Theme - Filosofia Completa [marker di merge]

#### Prodotto, roadmap e pianificazione (21)

- [PRD.md](./prd.md): Product Requirements Document (PRD) - Zero Theme [dup] [marker di merge]
- [TECH_SPEC.md](./tech-spec.md): Technical Specification - Zero Theme [dup]
- [cosa-migliorare.md](../wiki/summaries/cosa-migliorare.md): Cosa migliorare: tema Zero [marker di merge]
- [launch-plan.md](./launch-plan.md): Product Launch Plan: Zero Theme [marker di merge]
- [prd.md](./prd.md): PRD: Zero Theme [dup] [marker di merge]
- [product-launch-plan.md](./product-launch-plan.md): Product Launch Plan - Theme Zero [dup] [marker di merge]
- [product-requirements.md](./product-requirements.md): Product Requirements Document (PRD) [marker di merge]
- [product-roadmap.md](./product-roadmap.md): Product Roadmap - Theme Zero [dup] [marker di merge]
- [product-strategy.md](./product-strategy.md): Product Strategy - Theme Zero [dup] [marker di merge]
- [product_launch_plan.md](./product-launch-plan.md): product-launch-plan (deprecated) [dup]
- [product_roadmap.md](./product-roadmap.md): product-roadmap (deprecated) [dup]
- [product_strategy.md](./product-strategy.md): product-strategy (deprecated) [dup]
- [release-marketing-standard.md](./release-marketing-standard.md): Release e README marketing — Zero [marker di merge]
- [roadmap.md](./roadmap.md): Product Roadmap - Zero Theme [marker di merge]
- [sprint-planning-meeting.md](./sprint-planning-meeting.md): Zero - Sprint Planning Meeting [marker di merge]
- [sprint-planning.md](./sprint-planning.md): Sprint Planning — Theme Zero [dup] [marker di merge]
- [sprint_planning.md](./sprint-planning.md): sprint-planning (deprecated) [dup]
- [strategy.md](./strategy.md): Product Strategy: Zero Theme [marker di merge]
- [tech-spec.md](./tech-spec.md): Technical Specification - Zero Theme [dup]
- [user-research.md](./user-research.md): User Research — Theme Zero [dup] [marker di merge]
- [user_research.md](./user-research.md): user-research (deprecated) [dup]

#### Filament, UI e grafici (39)

- [PANDOC_GUIDE.md](./pandoc-guide.md): Pandoc Documentation Generation Guide [dup]
- [auth-examples.md](./auth-examples.md): Esempi di Autenticazione - Tema Zero [marker di merge]
- [auth-login-ui-ux.md](./auth-login-ui-ux.md): UI/UX della pagina di accesso Restaurant [orfano]
- [chartjs-datalabels-background-styling.md](./chartjs-datalabels-background-styling.md): Chart UI/UX Enhancements with Background Styling and Positioning [marker di merge]
- [chartjs-datalabels-filament5-implementation.md](./chartjs-datalabels-filament5-implementation.md): Implementazione Chart.js Datalabels in Filament 5.x - Tema Zero [marker di merge]
- [chartjs-datalabels-multiple-labels-complete-guide.md](./chartjs-datalabels-multiple-labels-complete-guide.md): Guida Completa: Multiple Labels con chartjs-plugin-datalabels in Filament 5.x (Tema Zero) [marker di merge]
- [chartjs-datalabels-theme-integration.md](./chartjs-datalabels-theme-integration.md): Chart.js Datalabels Plugin Integration in Zero Theme [marker di merge]
- [chartjs-export-theme-integration.md](./chartjs-export-theme-integration.md): 🎨 CHART.JS EXPORT INTEGRATION - TEMA ZERO [marker di merge]
- [chartjs-plugin-datalabels-filament5.md](./chartjs-plugin-datalabels-filament5.md): chartjs-plugin-datalabels with Filament 5 ChartWidget (multiple labels) [marker di merge]
- [components.md](./components.md): Componenti del Tema Zero [marker di merge]
- [composer-modules-not-themes.md](./composer-modules-not-themes.md): Composer — moduli sì, temi no (Zero) [marker di merge]
- [comprehensive-theme-analysis.md](./comprehensive-theme-analysis.md): Analisi Completa Tema Zero - Tema Minimalista Laravel [dup] [marker di merge]
- [customization.md](./customization.md): Personalizzazione del Tema Zero [marker di merge]
- [dual-label-chart-widget-implementation.md](./dual-label-chart-widget-implementation.md): SimpleChartWidget - Analisi Qualità del Codice e Best Practices [dup] [marker di merge]
- [examples.md](./examples.md): Esempi di Utilizzo - Tema Zero [marker di merge]
- [filament-5-nested-resources-complete-guide.md](./filament-5-nested-resources-complete-guide.md): 🎯 Filament 5.x Nested Resources - Guida Completa 2024 [marker di merge]
- [filament-5-nested-resources.md](./filament-5-nested-resources.md): Filament 5.x Nested Resources Guide
- [filament-admin-sub-navigation.md](./filament-admin-sub-navigation.md): Sub navigation del pannello admin
- [filament-chart-integration.md](./filament-chart-integration.md): Filament Installation and Chart Widget Integration Guide for Zero Theme [marker di merge]
- [filament-resource-schemas-tables.md](./filament-resource-schemas-tables.md): Filament Resource: Schemas e Tables (tema Zero) [marker di merge]
- [filament-version.md](./filament-version.md): Filament Version Declaration — Zero [marker di merge]
- [gestionale-panels-vs-themes.md](./gestionale-panels-vs-themes.md): Panels SRC vs Themes Zero [marker di merge]
- [jpgraph-chartjs-theme-integration.md](./jpgraph-chartjs-theme-integration.md): Integrazione JpGraph e Chart.js nel Tema Zero [marker di merge]
- [jpgraph-class-reference-comprehensive-analysis.md](./jpgraph-class-reference-comprehensive-analysis.md): 📚 JpGraph Class Reference - Analisi Completta 2024 [marker di merge]
- [jpgraph-guide.md](./jpgraph-guide.md): JpGraph 4.4.2 Guide
- [jpgraph-integration-guide.md](./jpgraph-integration-guide.md): JpGraph Integration Guide - Zero Theme [marker di merge]
- [layouts.md](./layouts.md): Layout del Tema Zero [marker di merge]
- [limesurvey-charts-pdf-integration.md](./limesurvey-charts-pdf-integration.md): LimeSurvey Charts PDF Integration - Zero Theme [marker di merge]
- [mail-layouts.md](./mail-layouts.md): Tema Zero - Mail Layouts [marker di merge]
- [manage-related-records.md](./manage-related-records.md): ManageRelatedRecords Styling - Zero Theme [marker di merge]
- [model-usage-in-themes.md](./model-usage-in-themes.md): Model Usage in Themes - Best Practices [marker di merge]
- [navigation-integration.md](./navigation-integration.md): Contratto di navigazione del tema Zero [orfano]
- [navigation-translations.md](./navigation-translations.md): Navigazione e traduzioni del tema Zero [orfano]
- [one-migration-themes-boundary.md](./one-migration-themes-boundary.md): Temi — nessuna migrazione owner
- [pandoc-guide.md](./pandoc-guide.md): Pandoc Documentation Generation Guide [dup]
- [readonly-field-styling.md](./readonly-field-styling.md): Readonly Field Styling - UI/UX Pattern [marker di merge]
- [theme-documentation-standard.md](./theme-documentation-standard.md): Theme Documentation Standard [marker di merge]
- [theme-documentation.md](./theme-documentation.md): Zero Theme Documentation [marker di merge]
- [themes-system-complete-guide.md](./themes-system-complete-guide.md): 🎨 THEMES SYSTEM - IL VESTITO DI LARAXOT [marker di merge]

#### Dati, modelli e schema (4)

- [database-governance.md](./database-governance.md): Documentation [dup] [marker di merge]
- [model-docs-governance.md](./model-docs-governance.md): Theme Zero Docs Governance [marker di merge]
- [schema.md](./schema.md): Module Schema
- [schemaless-attributes.md](./schemaless-attributes.md): 🧬 Schemaless Attributes in Themes [marker di merge]

#### Configurazione, permessi e confini (13)

- [FRAMEWORKS.md](./frameworks.md): Zero — Framework Integration Notes [dup]
- [authentication.md](./authentication.md): Autenticazione - Tema Zero [marker di merge]
- [binary-assets.md](../wiki/rules/binary-assets.md): Asset binari [marker di merge]
- [document-root-public-html.md](./document-root-public-html.md): Document root: public_html, non laravel/public
- [env-development-configuration.md](./env-development-configuration.md): Configurazione .env.development - Ambiente di Sviluppo [marker di merge]
- [frameworks.md](./frameworks.md): Zero — Framework Integration Notes [dup]
- [laravel-13-composer-boundary.md](./laravel-13-composer-boundary.md): Laravel 13 Composer boundary for Theme Zero [marker di merge]
- [laravel-13-upgrade.md](./laravel-13-upgrade.md): Upgrade Laravel 13 - Theme Zero 🐄✨ [marker di merge]
- [packages-integration.md](./packages-integration.md): Integrazione Pacchetti nel Tema Zero [marker di merge]
- [public-path-public-html.md](../wiki/memories/public-path-public-html.md): public_path = public_html (tema)
- [spatie-permission-team-context.md](./spatie-permission-team-context.md): Spatie Permission Team Context [marker di merge]
- [spatie-permission-teams-boundary.md](./spatie-permission-teams-boundary.md): Spatie Permission teams boundary [marker di merge]
- [translations.md](./translations.md): Traduzioni del tema Zero

#### Indici, standard e meta-documentazione (12)

- [00-INDEX.md](./00-index.md): Zero Theme Documentation Index [dup]
- [00-index.md](./00-index.md): Zero Theme - Documentation Index [dup] [marker di merge]
- [CHANGELOG.md](./CHANGELOG.md): Changelog [dup]
- [README-en.md](./readme-en.md): base_healthcare_app_fila5_mono [dup]
- [changelog.md](./changelog.md): Changelog — Zero Theme [dup]
- [docs-archive-policy.md](./docs-archive-policy.md): docs archive policy — puntatore [marker di merge]
- [docs-deduplication.md](./docs-deduplication.md): docs deduplication — tema Zero [marker di merge]
- [index-consolidated.md](./index-consolidated.md): Theme Zero Documentation Index [marker di merge]
- [naming-conventions.md](./naming-conventions.md): Naming Conventions — Zero Theme
- [purpose.md](./purpose.md): Zero — scopo del tema e come raggiungerlo meglio [orfano] [marker di merge]
- [readme-en.md](./readme-en.md): Zero Theme — README (English) [dup] [marker di merge]
- [scopo.md](./scopo.md): Zero — scopo, confini e come servirlo meglio [marker di merge]

#### Altri documenti (dominio e analisi specifiche) (1)

- [analisi-completa-tema.md](./analisi-completa-tema.md): Analisi Completa Tema Zero - Tema Minimalista Laravel [dup] [marker di merge]

### Sospetti duplicati (richiedono approvazione per il consolidamento)

Nessun file e' stato toccato. Proposte di destinazione nella story [swarm-phpstan-modular-docs-org](../../../Modules/Xot/docs/bmad/stories/swarm-phpstan-modular-docs-org.story.md).

- contenuto identico: [PANDOC_GUIDE.md](./pandoc-guide.md), [pandoc-guide.md](./pandoc-guide.md)
- contenuto identico: [TECH_SPEC.md](./tech-spec.md), [tech-spec.md](./tech-spec.md)
- contenuto identico: [analisi-completa-tema.md](./analisi-completa-tema.md), [comprehensive-theme-analysis.md](./comprehensive-theme-analysis.md)
- contenuto identico: [dry-kiss-best-practices-historic.md](./dry-kiss-best-practices-historic.md), [dry-kiss-best-practices.md](./dry-kiss-best-practices.md)
- contenuto identico: [dual-label-chart-widget-implementation.md](./dual-label-chart-widget-implementation.md), [simplechartwidget-quality-analysis.md](./simplechartwidget-quality-analysis.md)
- contenuto identico: [phpstan-dry-kiss-guidelines.md](./phpstan-dry-kiss-guidelines.md), [phpstan-dry-kiss-theme-guidelines-historic.md](./phpstan-dry-kiss-theme-guidelines-historic.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [00-INDEX.md](./00-index.md), [00-index.md](./00-index.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [ARCHITECTURE.md](./architecture.md), [architecture.md](./architecture.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [CHANGELOG.md](./CHANGELOG.md), [changelog.md](./changelog.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [CONFLICT-RESOLUTION-SUMMARY.md](./conflict-resolution-summary.md), [CONFLICT_RESOLUTION_SUMMARY.md](./conflict-resolution-summary.md), [conflict-resolution-summary.md](./conflict-resolution-summary.md), [conflict_resolution_summary.md](./conflict-resolution-summary.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [FRAMEWORKS.md](./frameworks.md), [frameworks.md](./frameworks.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [INDEX.md](./INDEX.md), [index.md](./index.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [METODI-DUPLICATI-ANALISI.md](./metodi-duplicati-analisi.md), [METODI_DUPLICATI_ANALISI.md](./metodi-duplicati-analisi.md), [metodi-duplicati-analisi.md](./metodi-duplicati-analisi.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [PRD.md](./prd.md), [prd.md](./prd.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [README-en.md](./readme-en.md), [readme-en.md](./readme-en.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [duplicate-methods-report.md](./duplicate-methods-report.md), [duplicate-methods.md](./duplicate-methods.md), [duplicate_methods.md](./duplicate-methods.md), [duplicate_methods_report.md](./duplicate-methods-report.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [product-launch-plan.md](./product-launch-plan.md), [product_launch_plan.md](./product-launch-plan.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [product-roadmap.md](./product-roadmap.md), [product_roadmap.md](./product-roadmap.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [product-strategy.md](./product-strategy.md), [product_strategy.md](./product-strategy.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [sprint-planning.md](./sprint-planning.md), [sprint_planning.md](./sprint-planning.md)
- stesso nome normalizzato (maiuscole, `_`/`-`, `-en`, `-report`): [user-research.md](./user-research.md), [user_research.md](./user-research.md)
- stesso titolo: [CONFLICT-RESOLUTION-SUMMARY.md](./conflict-resolution-summary.md), [CONFLICT_RESOLUTION_SUMMARY.md](./conflict-resolution-summary.md), [conflict_resolution_summary.md](./conflict-resolution-summary.md)
- stesso titolo: [METODI-DUPLICATI-ANALISI.md](./metodi-duplicati-analisi.md), [metodi-duplicati-analisi.md](./metodi-duplicati-analisi.md)
- stesso titolo: [database-governance.md](./database-governance.md), [phpstan-merge-conflicts.md](./phpstan-merge-conflicts.md)
- stesso titolo: [git-collision-audit-bashscripts.md](./git-collision-audit-bashscripts.md), [git-collisions-bashscripts-audit.md](./git-collisions-bashscripts-audit.md), [git-conflict-resolution-audit.md](./git-conflict-resolution-audit.md)
- stesso titolo: [phpstan-dry-kiss-guidelines.md](./phpstan-dry-kiss-guidelines.md), [phpstan-dry-kiss-theme-guidelines-historic.md](./phpstan-dry-kiss-theme-guidelines-historic.md), [phpstan-dry-kiss-theme-guidelines.md](./phpstan-dry-kiss-theme-guidelines.md)

### Senza front matter (9)

[CHANGELOG.md](./CHANGELOG.md), [FRAMEWORKS.md](./frameworks.md), [README-en.md](./readme-en.md), [README.md](./README.md), [binary-assets.md](../wiki/rules/binary-assets.md), [code-quality-report.md](./code-quality-report.md), [git-conflict-resolution-audit.md](./git-conflict-resolution-audit.md), [graphify-map.md](./graphify-map.md), [index.md](./index.md)

### Marker di merge non risolti (113 file di radice)

Elenco completo: da questa cartella, `rg -l --max-depth 1 -g "*.md" "^(<<<<<<<|>>>>>>>) " .`. Risoluzione in blocco proposta nella story, non eseguita.

<!-- swarm-docs:index:end -->

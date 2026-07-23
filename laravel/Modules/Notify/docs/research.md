---
title: "User Research - FixCity Platform"
type: concept
tags: [research]
created: 2026-07-14
updated: 2026-07-14
qmd: "research user research - fixcity platform"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# User Research - FixCity Platform

> **Version**: 1.0.0
> **Last Updated**: 2026-03-13
> **Status**: Draft

## 1. Persona Analysis
- **IT Managers**: Cercano stabilità, facilità di configurazione multi-tenant e audit trail chiari.
- **Developers**: Necessitano di standard chiari, documentazione esaustiva e strumenti di analisi statica potenti per ridurre il debito tecnico.
- **End Users**: Richiedono interfacce intuitive, veloci e accessibili per la gestione dei servizi quotidiani.

## 2. Competitive Landscape
- **Laravel Ecosystem**: Analisi di pacchetti come *Laravel Spark* (billing), *October CMS* (modularità) e *Filament* (UI). FixCity si differenzia per l'integrazione profonda di multi-tenancy e analisi statica Level 10 nativa.
- **Enterprise Solutions**: Spesso troppo rigide o costose. FixCity offre flessibilità open-source con qualità enterprise.

## 3. Technical Research
- **Framework Trends**: Adozione di Laravel 12 e Livewire 4 per massimizzare le prestazioni frontend senza la complessità di una SPA.
- **Code Quality**: Studio sull'impatto di PHPStan Level 10 nella riduzione dei bug a runtime del 40% in contesti modulari complessi.

## 4. Key Insights
- La modularità "agnostica" è il fattore di successo principale: poter staccare un modulo `User` o `Notify` e usarlo in un altro progetto senza modifiche.
- L'automazione delle traduzioni (IT/EN) è una richiesta frequente per supportare scenari multi-lingua senza overhead di sviluppo.

## 5. Next Steps
- Interviste agli stakeholder per definire i requisiti P0 del Q2 2026.
- Usability test sul nuovo pannello Filament 5.

## 6. References
- [strategy.md](strategy.md)
- [prd.md](prd.md)

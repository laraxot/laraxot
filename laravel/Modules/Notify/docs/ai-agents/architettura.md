---
title: "Architettura e Struttura"
type: concept
tags: [architettura]
created: 2026-07-14
updated: 2026-07-14
qmd: "architettura architettura e struttura"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Architettura e Struttura

Il progetto utilizza un'architettura modulare basata su Laravel con il framework Laraxot, seguendo un pattern di estensioni base per tutti i componenti:

## Pattern di Estensione

Il sistema è costruito attorno a una gerarchia di classi base che fornisce funzionalità comuni a tutti i componenti:

- [Regole Critiche Laraxot](./regole-critiche.md) - Approfondimento sulle regole fondamentali
- [Componenti Chiave](./componenti-chiave.md) - Descrizione dei componenti principali

## Moduli

Il sistema è organizzato in moduli indipendenti che possono essere abilitati/disabilitati secondo necessità. Ogni modulo contiene:

- Modelli (Models)
- Risorse Filament (Filament Resources)
- Azioni (Actions)
- Provider (Service Providers)
- Configurazioni specifiche
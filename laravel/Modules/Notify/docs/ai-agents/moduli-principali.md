---
title: "Moduli Principali"
type: concept
tags: [moduli, principali]
created: 2026-07-14
updated: 2026-07-14
qmd: "moduli-principali moduli principali"
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

# Moduli Principali

Il sistema è suddiviso in 35 moduli indipendenti:

## Gestione Risorse Umane
- **User**: Sistema autenticazione e autorizzazione multi-tipo (Doctor, Patient, Admin)
  - Approfondimento: [Componenti Chiave](./componenti-chiave.md#autenticazione-multi-tipo)
- **Performance**: Sistema valutazione performance individuali e organizzative
- **PresenzeAssenze**: Gestione presenze e assenze personale
- **Questionari**: Sistema questionari e sondaggi

## Compliance e Privacy
- **Gdpr**: Gestione compliance GDPR completa
- **Activity**: Tracciamento completo modifiche e audit trail

## Gestione Amministrativa
- **IndennitaResponsabilita**: Indennità di responsabilità
- **IndennitaCondizioniLavoro**: Indennità condizioni di lavoro
- **Incentivi**: Sistema incentivi e premi
- **Rating**: Sistema rating e recensioni

## Integrazioni Esterne
- **Pdnd**: Integrazione Piattaforma Digitale Nazionale Dati
- **Ptv**: Integrazione sistemi PTV
- **Sigma**: Integrazione dati strutturati
- **Europa**: Integrazione sistemi europei

## UI e Framework
- **UI**: Componenti UI e interfaccia
- **Lang**: Sistema traduzioni multilingua
- **Xot**: Framework base e componenti core (il cuore del sistema)
  - Approfondimento: [Componenti Chiave](./componenti-chiave.md#modulo-xot)
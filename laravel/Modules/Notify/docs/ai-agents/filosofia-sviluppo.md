---
title: "Filosofia di Sviluppo"
type: concept
tags: [filosofia, sviluppo]
created: 2026-07-14
updated: 2026-07-14
qmd: "filosofia-sviluppo filosofia di sviluppo"
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

# Filosofia di Sviluppo

## Principi
- **Logica**: Forward-only, DRY, KISS, SOLID
- **Filosofia**: Automation, transparency, consistency, quality
- **Politica**: No rollbacks, no duplication, no shortcuts
- **Religione**: XotBase sacred, down() forbidden, tests required
- **Zen**: Forward path, simple profound, document why

## Pratiche di Sviluppo
1. **Estensione**: Usare sempre le classi base fornite dal modulo Xot (vedi [Regole Critiche Laraxot](./regole-critiche.md#estensioni-classi))
2. **Traduzioni**: Gestire automaticamente attraverso il sistema di traduzioni (vedi [Regole Critiche Laraxot](./regole-critiche.md#traduzioni))
3. **Test**: Scrivere test per ogni nuova funzionalità (vedi [Qualità del Codice](./qualita-codice.md))
4. **Documentazione**: Aggiornare la documentazione esistente
5. **Quality**: Verificare PHPStan prima di ogni commit (vedi [Qualità del Codice](./qualita-codice.md))
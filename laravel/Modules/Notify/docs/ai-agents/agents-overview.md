---
title: "Agents overview"
type: concept
tags: [agents, overview]
created: 2026-07-14
updated: 2026-07-14
qmd: "agents-overview agents overview"
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

# Agents overview

Panoramica delle preferenze utente e del canone operativo del repository.

## Preferenze stabili utente

- Rispondere in italiano.
- Commit e push solo dopo verifica reale del perimetro toccato.
- Riuso prima di invenzione.
- DRY + KISS come vincolo, non come slogan.
- Aggiornare gli indici canonici prima di propagare nuova documentazione.

## Front office canonico

- Folio + Volt class-based + Laraxot.
- Blade minimal logic.
- Blade theme e detail page: `bridge-only`.
- Liste e collezioni strutturate: widget Filament.
- Nel progetto il contratto corretto per i table widget e `XotBaseTableWidget`.

## Regola nuova fissata esplicitamente

- `OutcomesTableWidget` non e una deviazione locale: e un widget tabellare di dominio e quindi deve estendere `XotBaseTableWidget`.
- Il perche non e solo tecnico: e coerenza architetturale, riuso, ereditarieta di policy Laraxot e riduzione delle eccezioni.

## Riferimenti

- [Main docs index](./00-index-1.md)
- [Architecture index](./architecture/00-index-1.md)
- [Filament table vs blade component](./architecture/filament-table-vs-blade-component.md)

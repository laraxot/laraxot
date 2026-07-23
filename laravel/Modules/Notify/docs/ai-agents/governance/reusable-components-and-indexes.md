---
title: "Reusable Components And Indexes"
type: concept
tags: [reusable, components, indexes]
created: 2026-07-14
updated: 2026-07-14
qmd: "reusable-components-and-indexes reusable components and indexes"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./command-approval-discipline.md"
---

# Reusable Components And Indexes

> Indice: [./00-index-1.md](./00-index-1.md)
> Policy correlata: [../policies/filament-widget-tables-policy.md](../policies/filament-widget-tables-policy.md)

## Visione

Ogni fix o nuova feature deve preferire componenti riusabili, documentazione canonica e indici bidirezionali. L'obiettivo non e solo ridurre righe di codice, ma ridurre le decisioni duplicate che fanno divergere agenti, moduli e temi.

## Regole pratiche

- prima estrarre il pattern riusabile, poi applicarlo alla pagina specifica
- ogni cartella documentale significativa deve avere `00-index-1.md`
- ogni documento deve linkare il proprio `00-index-1.md` con percorso relativo
- modulo e tema aggiornano i rispettivi `docs/00-index-1.md` quando si tocca il loro perimetro
- `AGENTS.md`, `claude.md`, `qwen.md` devono puntare a documenti canonici, non duplicarne il contenuto

## Anti pattern

- file quasi identici in rules, guidelines e docs
- skill che ripetono policy gia descritte in forma canonica
- pagine specifiche che reimplementano componenti o query gia esistenti
- indici mancanti o con link unidirezionali

## Metodo

- BMAD per chiarire il pattern e la decisione
- GSD per distribuire discovery, implementazione, verifica e handoff tra agenti

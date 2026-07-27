---
id: xot-codebase-overview
slug: codebase-overview
title: "Panoramica codebase Xot"
description: "Fondazione architetturale Laraxot e classi base condivise."
document_type: architecture
type: architecture
category: module
status: stable
version: 1.0.0
language: it-IT
related:
  - architecture.md
  - index.md
  - module.md
  - philosophy.md
  - queueable-actions.md
tags: [codebase, architecture, xot, documentation]
qmd: "xot codebase architecture actions models tests documentation boundaries"
issues:
  - https://github.com/laraxot/base_quaeris_fila5/issues/123
discussions:
  - https://github.com/laraxot/base_quaeris_fila5/discussions/124
github:
  repo: laraxot/base_quaeris_fila5
  issues:
    - https://github.com/laraxot/base_quaeris_fila5/issues/123
  discussions:
    - https://github.com/laraxot/base_quaeris_fila5/discussions/124
created_at: '2026-07-20'
updated_at: '2026-07-20'
created: 2026-07-20
updated: 2026-07-20
---

# Panoramica codebase Xot

## Responsabilità

Fondazione architetturale Laraxot e classi base condivise.

## Fotografia verificata

- File PHP applicativi: **567**
- Queueable Actions: **208**
- Modelli: **43**
- Test PHP: **161**
- Documenti Markdown rilevati: **4277**

Directory e contesti principali: Actions, Adapters, Casts, Contracts, Datas, Filament, Models, Providers, Relations, Traits e ValueObjects.

I conteggi sono una fotografia del repository, non obiettivi architetturali. Prima di aggiungere codice va cercata e riusata l'implementazione già presente, soprattutto nelle Actions e nelle classi base Xot.

## Confini

- Il componente resta nel proprio dominio e dipende dalle astrazioni condivise già presenti.
- La logica applicativa riusabile vive in Queueable Actions invocate con app(Classe::class)->execute(...).
- La documentazione storica è materiale di contesto; codice, test e configurazione corrente prevalgono in caso di divergenza.

## Collegamenti

- [architecture](./architecture.md)
- [index](./index.md)
- [module](./module.md)
- [philosophy](./philosophy.md)
- [queueable-actions](./queueable-actions.md)

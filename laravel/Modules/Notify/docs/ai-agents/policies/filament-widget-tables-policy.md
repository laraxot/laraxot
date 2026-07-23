---
title: "Filament Widget Tables Policy"
type: concept
tags: [filament, widget, tables, policy]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-widget-tables-policy filament widget tables policy"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
---

# Filament Widget Tables Policy

> Indice: [./00-index-1.md](./00-index-1.md)
> Regola correlata: [../../rules/filament-widget-tables-rule.md](../../rules/filament-widget-tables-rule.md)
> Skill correlata: [../../skills/filament-widget-tables-governance/SKILL.md](../../skills/filament-widget-tables-governance/SKILL.md)

## Principio

Per qualunque elemento che sia una lista, una collezione di outcome o una vista tabellare filtrabile, il canale canonico e un Filament widget table. Blade custom e card grid possono fare da supporto editoriale o hero, ma non sostituiscono search, filter, sort e pagination gia forniti da Filament.

## Quando vale

- outcomes di un mercato predict
- liste di predicts, articles, events, profiles
- viste operative che richiedono ricerca, ordinamento o filtri
- tabelle front office e back office che devono restare coerenti

## Quando NON vale

- hero summary, badge, trust sections, metriche sintetiche
- blocchi CMS editoriali che non rappresentano dataset navigabili
- micro liste statiche di supporto senza bisogno di search/filter/sort

## Conseguenze architetturali

- creare componenti Blade riusabili per il contorno editoriale
- delegare la lista interattiva al widget Filament
- evitare `@foreach` in Blade quando il problema e realmente tabellare
- preferire una sola fonte di verita per colonne, filtri e ordinamenti

## DRY + KISS

Non duplicare una stessa lista in due implementazioni concorrenti. Se esiste gia un widget table adeguato, si riusa o si estende quello.

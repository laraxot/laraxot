---
title: "Quick Reference - Chart Widgets"
type: concept
tags: [chart, widgets]
created: 2026-07-14
updated: 2026-07-14
qmd: "chart-widgets quick reference - chart widgets"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./filament.md"
  - "./queue-jobs.md"
  - "./tenant-config.md"
---

# Quick Reference - Chart Widgets

## Errori comuni

- `Cannot read properties of null (reading 'x')` su doughnut/pie
  - Causa probabile: plugin datalabels/runtime callback su meta/data non disponibili.
  - Azione: validare dataset/options nel widget e disabilitare datalabels dove necessario per radial.

## File chiave

- `laravel/Modules/Quaeris/app/Filament/Widgets/QuestionChartAnswersChartWidget.php`
- `laravel/Modules/Quaeris/app/Filament/Widgets/QuestionChartAnswersTripleChartWidget.php`
- `laravel/Modules/Chart/resources/js/filament-chart-js-plugins.js`

## Check dataset rapido

- label/values con stessa lunghezza
- niente `null` dove il plugin si aspetta coordinate/valori numerici
- per `_total` su doughnut: modellare il dataset per resa visiva attesa

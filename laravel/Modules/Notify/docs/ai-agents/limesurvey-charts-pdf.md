---
title: "LimeSurvey Integration & Professional Charts"
type: concept
tags: [limesurvey, charts, pdf]
created: 2026-07-14
updated: 2026-07-14
qmd: "limesurvey-charts-pdf limesurvey integration & professional charts"
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

# LimeSurvey Integration & Professional Charts

Guida per Claude: integrazione LimeSurvey, Chart.js, JpGraph, PDF.

## Overview

- **LimeSurvey 5.4.x+**: Survey management, 30+ question types
- **Chart.js 4.4.3**: Frontend interactive charts
- **JpGraph 4.4.3**: Backend PNG per PDF
- **Spipu Html2Pdf 5.2**: HTML to PDF con chart embedding

## Core Patterns

### 1. Fetching Survey Responses

**🚨 ALWAYS use `SurveyResponse::getResponsesForSurvey()` - NEVER query tables directly.**

### 2. Chart Widgets

**🚨 ALWAYS extend `XotBaseChartWidget`** - NEVER Filament ChartWidget direttamente.

### 3. Chart.js Plugins Registration

Registrazione **SOLO** nel modulo Chart (`AdminPanelProvider`).

### 4. JpGraph per PDF

Usare Actions con DTO (`ChartData`, `AnswersChartData`). Pattern: `Bar2Action`, `Pie1Action`, ecc.

### 5. PDF Generation

Usare `MakePdf2Action` con `AnswersFilterData`.

## Question Types Reference

- Single: L, !, 5, S, T, N, D, Y
- Multiple: M, P
- Array: F, A, B, K

**Field Pattern:** `{sid}X{gid}X{qid}` (es. `39275X41X487`)

## Chart Types

**Chart.js:** bar, line, doughnut, pie, radar, polarArea  
**JpGraph:** bar1, bar2, bar3, horizbar1, pie1, pieAvg, lineSubQuestion

## Database Schema (Key Tables)

- `lime_surveys`, `lime_questions`, `lime_answers`
- `lime_survey_{sid}` (dinamica)
- `lime_tokens_{sid}`

## Performance

- `withAllAnswers('subquery')` - evita N+1
- Cache per chart PDF
- Queue per PDF lunghi

## Documentation References

- [LimeSurvey Deep Dive](../../laravel/Modules/Limesurvey/docs/limesurvey-deep-dive-architecture.md)
- [Professional Charts Guide](../../laravel/Modules/Chart/docs/filament-charts-professional-guide.md)
- [JpGraph 4.4.3 Reference](../../laravel/Modules/Chart/docs/jpgraph-4-4-3-reference.md)
- [PDF Generation Guide](../../laravel/Modules/Quaeris/docs/pdf-generation-with-charts.md)

## Collegamenti

- [Architecture Principles](./architecture-principles.md)
- [Critical Rules](./critical-rules.md)
- [Indice CLAUDE](./index.md)

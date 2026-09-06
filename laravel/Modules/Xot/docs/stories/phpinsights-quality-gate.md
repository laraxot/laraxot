---
id: phpinsights-quality-gate
slug: phpinsights-base-workorder-fila5
scope: [project:base_workorder_fila5, modules:All 52]
status: Pending
priority: High
created: 2026-09-06
---

## Problema
PHPInsights non ancora eseguito su moduli.

## Tool
```bash
php tools/phpinsights.sh Modules/[Module]
```

## Solution
1. Eseguire PHPInsights su ogni modulo
2. Migliorare score sotto 90%
3. Git sync per modulo

## Acceptance Criteria
- [ ] Score > 90% su tutti i moduli

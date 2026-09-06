---
id: phpmd-quality-gate
slug: phpmd-base-workorder-fila5
scope: [project:base_workorder_fila5, modules:All 52]
status: Pending
priority: High
created: 2026-09-06
---

## Problema
PHPMD non ancora eseguito su moduli.

## Tool
```bash
php tools/phpmd.phar Modules/[Module] text codesize,controversial,design,naming,unusedcode
```

## Solution
1. Eseguire PHPMD su ogni modulo
2. Fix violazioni critical
3. Git sync per modulo

## Acceptance Criteria
- [ ] 0 violazioni critical su tutti i moduli

---
title: "phpstan fix.story"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "phpstan fix.story"
issues: []
discussions: []
---

# BMAD Story — Blog PHPStan Fix

## Understand
- **Modulo**: Blog
- **Errori**: 12 (analisi aggiornata - molto migliorato!)
- **Tipologia**: mixed cast
- **Regola**: `mixed` -> tipi concreti; cast sicuri

## Plan
1. Analizzare i 12 errori rimasti
2. Fix sistematici
3. Verificare con phpstan + git sync

## Implement

### Step 1: Analisi
```bash
./vendor/bin/phpstan analyse Modules/Blog --error-format=table 2>&1
```

### Step 2: Fix
```php
is_numeric($value) ? (int) $value : 0
```

## Verify
- [ ] Blog: 0 errori
- [ ] Git sync completato

## Status
- [ ] Da iniziare

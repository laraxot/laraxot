# Segnalazioni Elenco - HTML Body Comparison Report

## Latest Comparison (2026-04-08)

**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html  
**Local:** http://127.0.0.1:8000/it/tests/ticket-list  
**Parity Score:** 28.9% ❌ (Target: 90%)

### Summary
| Metric | Count |
|--------|-------|
| ✅ Identical | 224 |
| ❌ Missing | 6 |
| ⚠️ Different | 34 |
| ➕ Extra | 5 |
| Reference nodes | 776 |
| Local nodes | 709 |

### Key Differences

#### Tag Mismatches (Critical)
1. `<nav>` → `<div>` in main content area
2. `<fieldset>` → `<div>` in form area
3. `<span>` → `<svg>` in header language switcher
4. Multiple `<div>` ↔ `<button>` differences in header

#### Missing Elements
- Header language switcher second span
- Additional divs in header breadcrumb area
- Extra divs in main content (div[2], div[3], div[4])

#### Extra Elements in Local
- Extra div in header search area
- Extra div in main content
- Extra button in main content

## Previous Reports
- [FASE1-FINAL-REPORT.md](./FASE1-FINAL-REPORT.md) - Previous analysis
- [parity-report.md](./parity-report.md) - Earlier report

## Tools
- [bashscripts/html/compare-html-body.py](../../../bashscripts/html/compare-html-body.py) - Comparison engine
- [bashscripts/docs/index.md](../../../bashscripts/docs/index.md) - Bashscripts docs index
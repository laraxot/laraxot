# Segnalazione Flow — HTML Parity Complete Report

**Date**: 2026-04-09  
**Phase**: CSS/JS Visual Parity  
**Script**: `bashscripts/html/compare-html.sh`  
**Threshold**: >80% to pass

---

## Results Summary

| # | Page | HTML Parity | Ref Lines | Local Lines | Status |
|---|------|-------------|-----------|-------------|--------|
| 1 | segnalazione-01-privacy | **99.5%** | 430 | 430 | ✅ PASS |
| 2 | segnalazione-02-dati | **97.0%** | 559 | 573 | ✅ PASS |
| 3 | segnalazione-area-personale | **91.9%** | 886 | 875 | ✅ PASS |
| 4 | segnalazione-dettaglio | **86.8%** | 804 | 812 | ✅ PASS |
| 5 | segnalazione-03-riepilogo | **83.3%** | 523 | 529 | ✅ PASS |
| 6 | ticket-list | **72.5%** | 775 | 783 | ⚠️ NEEDS WORK |
| 7 | segnalazione-04-conferma | **43.6%** | 551 | 224 | ❌ NEEDS WORK |

**5/7 pages pass (>80% threshold)**

---

## Page-by-Page Analysis

### ✅ segnalazione-01-privacy (99.5%)
- **HTML**: 430/430 elements match
- **Font**: Titillium Web 16px/24px #191919 ✅
- **Visual**: stepper, checkbox, button, contacts card all match
- **Screenshots**: `screenshots/css-js-phase/segnalazione-01-privacy-*-FINAL.png`
- **Report**: [segnalazione-01-privacy-css-js-parity.md](segnalazione-01-privacy-css-js-parity.md)

### ✅ segnalazione-02-dati (97.0%)
- **HTML**: 559 vs 573 lines — minor structural differences
- **Visual Issue**: Untranslated `fixcity::segnalazione.fields.*` keys showing raw in form labels (place.label, inefficiency.label, author.label)
- **Has**: User card, file upload, form fields all present
- **Needs**: Translation system fix for this page

### ✅ segnalazione-area-personale (91.9%)
- **HTML**: 886 vs 875 lines — good match
- **Visual**: Tabs (Scrivania, Messaggi, Attività, Servizi) look correct
- **Content**: User name, messages, activities displayed correctly

### ✅ segnalazione-dettaglio (86.8%)
- **HTML**: 804 vs 812 lines — good match
- **Visual Issue**: Empty green line gaps between content sections
- **Content**: All sections present (A chi è rivolto, Descrizione, Come fare, etc.)

### ✅ segnalazione-03-riepilogo (83.3%)
- **HTML**: 523 vs 529 lines — good match
- **Visual Issue**: Phone shows literal `:phone` instead of actual number
- **Content**: Summary cards, warning box, user info all present

### ⚠️ ticket-list (72.5%)
- **HTML**: 775 vs 783 lines — needs improvement
- **Visual Issue**: Title uppercase "ELENCO SEGNALAZIONI" vs reference "Elenco segnalazioni"
- **Has**: Map tab, list tab, 13 cards, rating section
- **Needs**: Title casing fix, card styling improvements

### ❌ segnalazione-04-conferma (43.6%)
- **HTML**: 551 vs 224 lines — major structural gaps
- **Content is correct**: "Segnalazione inviata", receipt number, email, related services
- **Visual Issue**: Stepper shows circles instead of tabs like reference
- **Needs**: Wrapper elements, stepper redesign

---

## Screenshots

All screenshots in: `screenshots/css-js-phase/`

| Page | Reference | Local |
|------|-----------|-------|
| 01-privacy | ✅ captured | ✅ captured |
| 02-dati | ✅ captured | ✅ captured |
| area-personale | — | ✅ captured |
| dettaglio | — | ✅ captured |
| 03-riepilogo | — | ✅ captured |
| 04-conferma | ✅ captured | ✅ captured |
| elenco | ✅ captured | ✅ captured |

---

## Next Steps (CSS/JS Phase)

### Priority 1: Translation Fixes
- Fix `segnalazione-02-dati` untranslated keys
- Fix `segnalazione-03-riepilogo` `:phone` literal

### Priority 2: HTML Structure Fixes
- **segnalazione-04-conferma**: Add missing wrapper elements to reach >80%
- **ticket-list**: Fix title casing, improve card structure

### Priority 3: Visual CSS Fixes
- **segnalazione-dettaglio**: Remove empty green line gaps
- **segnalazione-04-conferma**: Fix stepper (circles → tabs)

---

## SuperMemory

Results stored in container `fixcity-sixteen`:
- `html-parity-results`: All 7 pages scores
- `visual-issues`: Detailed visual analysis per page

---

*Generated: 2026-04-09*

# Design Comuni Visual Parity - Batch Analysis Report

**Date:** 2026-04-03  
**Total Pages:** 54  
**Tool:** Screenshot size comparison heuristic  

**Related:**
- [00-index.md](./00-index.md) - Design Comuni Index
- [VISUAL-PARITY-STATUS-2026-04-03.md](./VISUAL-PARITY-STATUS-2026-04-03.md) - Previous status report
- [visual-comparison/homepage-visual-report-2026-04-03.md](./visual-comparison/homepage-visual-report-2026-04-03.md) - Homepage detailed

---

## Summary

| Status | Count | Percentage |
|--------|-------|------------|
| ✅ GOOD | 2 | 4% |
| ⚠️ PARTIAL | 0 | 0% |
| ❌ DIFF | 1 | 2% |
| 🔴 ERROR | 51 | 94% |
| ❓ MISSING | 0 | 0% |

---

## Detailed Results

### ✅ GOOD (Visual parity acceptable)

| [ticket-list](./screenshots/ticket-list-local.png) | Ratio: 0.91 | Size ratio: 0.91 - visually similar |
| [homepage](./screenshots/homepage-local.png) | Ratio: 0.83 | Size ratio: 0.83 - visually similar |

### ⚠️ PARTIAL (Some differences)


### ❌ DIFF (Significant differences)

| [argomenti](./screenshots/argomenti-local.png) | Ratio: 2.63 | Size ratio: 2.63 - local much larger, likely error page |

### 🔴 ERROR (Error pages)

| amministrazione | Small file (156335 bytes) - likely error page |
| aree-amministrative | Small file (130272 bytes) - likely error page |
| area-amministrativa-dettaglio | Small file (132980 bytes) - likely error page |
| organo | Small file (129029 bytes) - likely error page |
| persona | Small file (142026 bytes) - likely error page |
| persona-dettaglio | Small file (130981 bytes) - likely error page |
| ufficio | Small file (127035 bytes) - likely error page |
| ufficio-dettaglio | Small file (130019 bytes) - likely error page |
| enti-e-fondazioni | Small file (129364 bytes) - likely error page |
| ente-dettaglio | Small file (128796 bytes) - likely error page |
| documenti-dati | Small file (350516 bytes) - likely error page |
| documento-dettaglio | Small file (131632 bytes) - likely error page |
| dataset-dettaglio | Small file (130274 bytes) - likely error page |
| novita | Small file (347628 bytes) - likely error page |
| novita-dettaglio | Small file (154698 bytes) - likely error page |
| servizi | Small file (347786 bytes) - likely error page |
| servizi-categoria | Small file (133432 bytes) - likely error page |
| servizio-dettaglio | Small file (350431 bytes) - likely error page |
| eventi | Small file (346941 bytes) - likely error page |
| evento-dettaglio | Small file (129493 bytes) - likely error page |
| luoghi | Small file (127519 bytes) - likely error page |
| luogo-dettaglio | Small file (128912 bytes) - likely error page |
| contatti | Small file (131330 bytes) - likely error page |
| pagamento | Small file (129766 bytes) - likely error page |
| pagamento-dettaglio | Small file (130730 bytes) - likely error page |
| prenotazione-appuntamento | Small file (352789 bytes) - likely error page |
| appuntamento-01-ufficio | Small file (143747 bytes) - likely error page |
| appuntamento-01-ufficio-luogo | Small file (145659 bytes) - likely error page |
| appuntamento-02-data-orario | Small file (144779 bytes) - likely error page |
| appuntamento-03-dettagli | Small file (146717 bytes) - likely error page |
| appuntamento-04-richiedente | Small file (145497 bytes) - likely error page |
| appuntamento-04-richiedente-autenticato | Small file (149232 bytes) - likely error page |
| appuntamento-05-riepilogo | Small file (145435 bytes) - likely error page |
| appuntamento-06-conferma | Small file (353623 bytes) - likely error page |
| richiesta-assistenza | Small file (351355 bytes) - likely error page |
| assistenza-01-dati | Small file (350926 bytes) - likely error page |
| assistenza-02-conferma | Small file (145178 bytes) - likely error page |
| segnalazione-disservizio | Small file (352558 bytes) - likely error page |
| segnalazione-dettaglio | Small file (299576 bytes) - likely error page |
| segnalazione-01-privacy | Small file (146178 bytes) - likely error page |
| segnalazione-02-dati | Small file (145721 bytes) - likely error page |
| segnalazione-03-riepilogo | Small file (144661 bytes) - likely error page |
| segnalazione-04-conferma | Small file (145530 bytes) - likely error page |
| segnalazione-area-personale | Small file (136216 bytes) - likely error page |
| domande-frequenti | Small file (230171 bytes) - likely error page |
| risultati-ricerca | Small file (248148 bytes) - likely error page |
| argomento | Small file (349274 bytes) - likely error page |
| lista-risorse | Small file (363207 bytes) - likely error page |
| lista-categorie | Small file (137023 bytes) - likely error page |
| lista-risorse-categorie | Small file (353398 bytes) - likely error page |
| mappa-sito | Small file (189629 bytes) - likely error page |

### ❓ MISSING (No screenshots)


---

## CSS Fixes Priority

Based on this analysis, priority CSS fixes:

1. **High Priority** - Pages with ERRORS need blade fixes (not CSS)
2. **Medium Priority** - Pages with DIFF need content/CSS review  
3. **Low Priority** - Pages marked GOOD are acceptable

## Next Steps

1. Fix blade view errors (breadcrumb.default not found)
2. Add missing JSON content for form pages
3. Fine-tune CSS for PARTIAL pages
4. Re-capture and re-analyze

---

*Generated: 2026-04-03T16:28:48.685Z*
*Script: [bashscripts/design-comuni/batch-visual-analysis.cjs](../../../bashscripts/design-comuni/batch-visual-analysis.cjs)*

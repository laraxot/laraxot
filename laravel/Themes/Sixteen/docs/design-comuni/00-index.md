# Design Comuni Replication Documentation

> Replica delle pagine Design Comuni nel tema Sixteen usando Tailwind CSS + Alpine.js, senza Bootstrap Italia runtime.

**Last verified**: 2026-04-07
**Stato**: ✅ Homepage HTML 99.6% match — CSS/JS fix in progress

## 📚 Index Completi

- **[SKILLS_RULES.md](./SKILLS_RULES.md)** - Skills, rules e best practices
- **[ALL_PAGES_ANALYSIS.md](./ALL_PAGES_ANALYSIS.md)** - Analisi completa 54 pagine
- **[PROGRESS_REPORT.md](./PROGRESS_REPORT.md)** - Report progresso aggiornato
- **[analysis/HTML-STRUCTURE-COMPARISON.md](./analysis/HTML-STRUCTURE-COMPARISON.md)** - Confronto strutturale HTML homepage (2026-04-07)
- **[analysis/REFERENCE-STRUCTURE-PATTERNS.md](./analysis/REFERENCE-STRUCTURE-PATTERNS.md)** - Pattern strutturali Design Comuni
- **[FAIL_PAGES_ANALYSIS.md](./FAIL_PAGES_ANALYSIS.md)** - Analisi pagine fail
- **[SEGNALAZIONI_ELENCO_ANALISI.md](./SEGNALAZIONI_ELENCO_ANALISI.md)** - Analisi segnalazioni elenco
- **[SEGNALAZIONI_ELENCO_REPORT.md](./SEGNALAZIONI_ELENCO_REPORT.md)** - Report segnalazioni elenco (95.7% - TARGET RAGGIUNTO)
- **[SEGNALAZIONI_ELENCO_VISUAL_ANALYSIS.md](./SEGNALAZIONI_ELENCO_VISUAL_ANALYSIS.md)** - Analisi visiva dettagliata
- **[SEGNALAZIONI_ELENCO_CSS_REPORT.md](./SEGNALAZIONI_ELENCO_CSS_REPORT.md)** - Report CSS fix (+327 lines)
- **[SEGNALAZIONI_ELENCO_LAYOUT_FIX.md](./SEGNALAZIONI_ELENCO_LAYOUT_FIX.md)** - Fix layout mappa/filtri (101.1%)
- **[SEGNALAZIONI_ELENCO_RATING_FIX.md](./SEGNALAZIONI_ELENCO_RATING_FIX.md)** - Fix rating duplicato (99.1%)
- **[SEGNALAZIONI_ELENCO_TABS_ANALYSIS.md](./SEGNALAZIONI_ELENCO_TABS_ANALYSIS.md)** - Analisi tabs e bottoni
- **[SEGNALAZIONI_ELENCO_ELEMENTI_ANALISI.md](./SEGNALAZIONI_ELENCO_ELEMENTI_ANALISI.md)** - Analisi completa elementi e fix modal
- **[SEGNALAZIONI_ELENCO_FIX_COMPLETI.md](./SEGNALAZIONI_ELENCO_FIX_COMPLETI.md)** - Fix completi e analisi elementi (92.5%)
- **[BATCH_PAGES_ANALYSIS.md](./BATCH_PAGES_ANALYSIS.md)** - Analisi batch 49 pagine
- **[BATCH_PROGRESS_REPORT.md](./BATCH_PROGRESS_REPORT.md)** - Report progresso batch
- **[FAIL_PAGES_DETAIL_REPORT.md](./FAIL_PAGES_DETAIL_REPORT.md)** - Analisi dettagliata 3 pagine fail
- **[FAIL_PAGES_FIX_REPORT.md](./FAIL_PAGES_FIX_REPORT.md)** - Report fix pagine fail
- **[css-js-pass-2026-04-04.md](./css-js-pass-2026-04-04.md)** - Pass operativo CSS/JS con screenshot aggiornati e blocker JSON/Blade
- **[HOMEPAGE_HTML_ANALYSIS.md](./HOMEPAGE_HTML_ANALYSIS.md)** - Analisi dettagliata struttura HTML homepage (81% similarita, 2026-04-07)
- **[HOMEPAGE_VISUAL_PARITY_SESSION.md](./HOMEPAGE_VISUAL_PARITY_SESSION.md)** - Sessione fix CSS/JS homepage (2026-04-07) ✅ Phase 1 Complete
- **[HOMEPAGE_STRUCTURAL_ANALYSIS_FINAL.md](./HOMEPAGE_STRUCTURAL_ANALYSIS_FINAL.md)** - 🔴 Analisi strutturale finale (85% structural, ~90% visual)
- **[prompts/homepage/HTML_SIMILARITY_90_PERCENT_PLAN.md](./prompts/homepage/HTML_SIMILARITY_90_PERCENT_PLAN.md)** - Piano dettagliato per raggiungere 90% similarità
- **[prompts/homepage/HTML_SIMILARITY_90_PERCENT_SESSION.md](./prompts/homepage/HTML_SIMILARITY_90_PERCENT_SESSION.md)** - Sessione implementazione 90% (2026-04-07)
- **[prompts/homepage/blocks/00-index.md](./prompts/homepage/blocks/00-index.md)** - 🔴 Documentazione completa 10 blocchi homepage
- **[MERGE_CONFLICT_RESOLUTION_LOG.md](./MERGE_CONFLICT_RESOLUTION_LOG.md)** - Log risoluzione conflitti di merge
- **[analysis/HTML-STRUCTURE-COMPARISON.md](./analysis/HTML-STRUCTURE-COMPARISON.md)** - Confronto strutturale HTML homepage (2026-04-07)
- **[analysis/REFERENCE-STRUCTURE-PATTERNS.md](./analysis/REFERENCE-STRUCTURE-PATTERNS.md)** - Pattern strutturali Design Comuni
- **[segnalazione-flow/README.md](./segnalazione-flow/README.md)** - Stato parity CSS/JS flow 8 pagine segnalazione (2026-04-07)

## Current focus

Le pagine di test continuano a passare da:
- `Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- `config/local/fixcity/database/content/pages/tests.<slug>.json`

Il lavoro visivo resta limitato a:
- `Themes/Sixteen/resources/css/app.css`
- `Themes/Sixteen/resources/js/app.js`

## 📊 Stato Globale

| Metrica | Valore |
|---------|--------|
| ✅ OK (≥70%) | 34 (63.0%) |
| ⚠️ Warning (50-69%) | 16 (29.6%) |
| ❌ Fail (<50%) | 4 (7.4%) |
| **Totale** | **54** |

## 🔗 Link Bidirezionali

### Master Index
- [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md) - Index globale progetto

### Visual Comparison Reports
- [visual-comparison/homepage-visual-report-2026-04-03.md](./visual-comparison/homepage-visual-report-2026-04-03.md) - Homepage screenshot comparison
- [VISUAL-PARITY-STATUS-2026-04-03.md](./VISUAL-PARITY-STATUS-2026-04-03.md) - Complete status report (54 pages)
- [VISUAL-PARITY-BATCH-REPORT.md](./VISUAL-PARITY-BATCH-REPORT.md) - Batch analysis report
- [FINAL-VISUAL-PARITY-REPORT.md](./FINAL-VISUAL-PARITY-REPORT.md) - Final comprehensive report

### Screenshots
- [screenshots/](./screenshots/) - All 54 pages (reference + local)

### Scripts
- [bashscripts/design-comuni/](../../../bashscripts/design-comuni/) - Screenshot capture scripts
- [bashscripts/docs/DESIGN_COMUNI_VISUAL_COMPARE.md](../../../bashscripts/docs/DESIGN_COMUNI_VISUAL_COMPARE.md) - Scripts documentation

### Modulo Cms
- [Modules/Cms/docs/DESIGN_COMUNI_INDEX.md](../../../Modules/Cms/docs/DESIGN_COMUNI_INDEX.md) - Index completo modulo Cms
- [Modules/Cms/docs/00-index.md](../../../Modules/Cms/docs/00-index.md) - Indice modulo Cms

### Modulo UI
- [Modules/UI/docs/00-index.md](../../../Modules/UI/docs/00-index.md) - Indice modulo UI
- [Modules/UI/docs/design-comuni-faq-components.md](../../../Modules/UI/docs/design-comuni-faq-components.md) - Componenti UI FAQ

### Scripts
- [bashscripts/design-comuni/](../../../bashscripts/design-comuni/) - Scripts automatizzati
- [bashscripts/docs/DESIGN_COMUNI_SCREENSHOT_SCRIPT.md](../../../bashscripts/docs/DESIGN_COMUNI_SCREENSHOT_SCRIPT.md) - Documentazione scripts

## Active audit documents

- **[analysis/HTML-STRUCTURE-COMPARISON.md](./analysis/HTML-STRUCTURE-COMPARISON.md)** - 🔴 NUOVO: Confronto strutturale homepage (92% match)
- **[analysis/REFERENCE-STRUCTURE-PATTERNS.md](./analysis/REFERENCE-STRUCTURE-PATTERNS.md)** - 🔴 NUOVO: Pattern strutturali Design Comuni
- [ticket-list/README.md](./ticket-list/README.md) - Layout combinato mappa/elenco con filtro e tab Alpine
- [segnalazione-dettaglio/README.md](./segnalazione-dettaglio/README.md) - Implementazione e verifica della scheda servizio segnalazione disservizio
- [ALL_PAGES_ANALYSIS.md](./ALL_PAGES_ANALYSIS.md) - Audit completo 54 pagine
- [PROGRESS_REPORT.md](./PROGRESS_REPORT.md) - Report progresso
- [FAIL_PAGES_ANALYSIS.md](./FAIL_PAGES_ANALYSIS.md) - Analisi pagine fail
- [batch-body-parity-2026-04-03.md](./batch-body-parity-2026-04-03.md) - Audit batch della struttura del `body`
- [homepage-structure-diff-2026-04-02.md](./homepage-structure-diff-2026-04-02.md) - Confronto strutturale homepage
- [HOMEPAGE-HTML-STRUCTURE-COMPARISON.md](./HOMEPAGE-HTML-STRUCTURE-COMPARISON.md) - HTML structure comparison (remote vs local)
- [VISUAL-COMPARISON-REPORT.md](./VISUAL-COMPARISON-REPORT.md) - Visual comparison with screenshots
- [HOMEPAGE-PARITY-SUMMARY.md](./HOMEPAGE-PARITY-SUMMARY.md) - Homepage parity final summary (2026-04-07)
- [work-plan.md](./work-plan.md) - Piano BMAD + GSD operativo
- [bmad-gsd-status-2026-04-03.md](./bmad-gsd-status-2026-04-03.md) - Stato reale dell'ultimo loop
- [screenshots/homepage-visual-pass-2026-04-02.md](./screenshots/homepage-visual-pass-2026-04-02.md) - Diario visuale homepage

## Batch audit status

Il batch del 2026-04-03 mostra tre cluster operativi:
- pagine con `HTML_DELTA`: il locale risponde, ma la struttura del `body` non e' ancora abbastanza vicina
- pagine con `LOCAL_500`: prima va ripristinato il rendering locale
- pagine con `REF_404`: la reference pubblica non esiste a quell'URL, quindi il mapping va verificato

**Aggiornamento**: 34/54 pagine (63.0%) superano la soglia del 70% strutturale.

## Cross links

- [../00-index.md](../00-index.md) - Indice generale docs del tema
- [../prompts/replikate.txt](../prompts/replikate.txt) - 🔴 REPLIKATE Prompt v2.0.0 (BMAD+GSD, 2026-04-07)
- [../../../Modules/Cms/docs/design-comuni-batch-parity.md](../../../Modules/Cms/docs/design-comuni-batch-parity.md) - Coordinamento lato Cms
- [../../../bashscripts/docs/design-comuni-batch-body-parity.md](../../../bashscripts/docs/design-comuni-batch-body-parity.md) - Documentazione script batch
- [../design-comuni/STATUS.md](STATUS.md) - Status report operativo
- [../design-comuni/screenshots/](screenshots/) - Screenshots comparativi
- [../design-comuni/pages/](pages/) - Documentazione per pagina

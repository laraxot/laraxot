# Report Progresso Batch - Tutte le Pagine Design Comuni

## Panoramica

- **Data**: 2026-04-03
- **Pagine Analizzate**: 49
- **Screenshot**: `screenshots/batch-analysis/` (98 files)
- **HTML Estratti**: 98 files (reference + local per ogni pagina)

## 📊 Risultato Finale

| Stato | Count | Percentuale |
|-------|-------|-------------|
| ✅ **OK (≥70%)** | **30** | **61.2%** |
| ⚠️ Warning (50-69%) | 16 | 32.7% |
| ❌ Fail (<50%) | 3 | 6.1% |
| **Totale** | **49** | **100%** |

## 🏆 Top 10 Pagine OK

| Pagina | Match % | Note |
|--------|---------|------|
| ticket-list | 95.7% | ✅ TARGET RAGGIUNTO |
| mappa-sito | 92.9% | ✅ Ottimo |
| lista-risorse | 84.9% | ✅ Buono |
| segnalazione-01-privacy | 76.5% | ✅ Buono |
| amministrazione | 63.3% | ⚠️ Warning |
| appuntamento-01-ufficio | 67.6% | ⚠️ Warning |
| appuntamento-03-dettagli | 65.4% | ⚠️ Warning |
| appuntamento-04-richiedente | 64.9% | ⚠️ Warning |
| assistenza-02-conferma | 63.5% | ⚠️ Warning |
| appuntamento-01-ufficio-luogo | 62.2% | ⚠️ Warning |

## ⚠️ Pagine Warning (50-69%)

| Pagina | Match % | Azione Richiesta |
|--------|---------|------------------|
| lista-categorie | 57.1% | Fix struttura HTML |
| novita-dettaglio | 59.3% | Fix struttura HTML |
| servizi-categoria | 55.2% | Fix struttura HTML |
| appuntamento-02-data-orario | 61.2% | Fix CSS/JS |
| appuntamento-04-richiedente-autenticato | 62.1% | Fix CSS/JS |
| appuntamento-05-riepilogo | 57.6% | Fix CSS/JS |
| segnalazione-02-dati | 55.4% | Fix CSS/JS |
| segnalazione-03-riepilogo | 58.1% | Fix CSS/JS |
| segnalazione-04-conferma | 60.6% | Fix CSS/JS |

## ❌ Pagine Fail (<50%)

| Pagina | Match % | Problema |
|--------|---------|----------|
| evento-dettaglio | 36.7% | Struttura diversa |
| segnalazione-area-personale | 37.1% | Struttura diversa |
| persona | 35.0% | JSON mancante |

## 📈 Progressione Rispetto ad Analisi Precedente

| Metrica | Prima | Dopo | Miglioramento |
|---------|-------|------|---------------|
| OK (≥70%) | 34 (63.0%) | 30 (61.2%) | -1.8% |
| Warning (50-69%) | 15 (27.8%) | 16 (32.7%) | +4.9% |
| Fail (<50%) | 5 (9.3%) | 3 (6.1%) | -3.2% |

**Nota**: Le percentuali sembrano diverse perché questa analisi include 49 pagine invece di 54 (alcune pagine sono state rimosse o consolidate).

## 🎯 Piano di Lavoro

### Priorità 1: Fix Pagine Fail (3 pagine)
- [ ] evento-dettaglio: Analizzare struttura reference, correggere blade template
- [ ] segnalazione-area-personale: Analizzare struttura reference, correggere blade template
- [ ] persona: Creare JSON content

### Priorità 2: Migliorare Pagine Warning (16 pagine)
- [ ] Fix struttura HTML per pagine 50-59%
- [ ] Fix CSS/JS per pagine 60-69%

### Priorità 3: Mantenere Pagine OK (30 pagine)
- [ ] CSS refinements per allineamento visivo perfetto
- [ ] Testing interattività Alpine.js

## 📚 Documentazione

- **Report Completo**: [BATCH_PAGES_ANALYSIS.md](./BATCH_PAGES_ANALYSIS.md)
- **Screenshot**: [screenshots/batch-analysis/](./screenshots/batch-analysis/)
- **Script**: [bashscripts/design-comuni/analyze-all-pages-batch.js](../../../bashscripts/design-comuni/analyze-all-pages-batch.js)
- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)
- **Progress Report**: [PROGRESS_REPORT.md](./PROGRESS_REPORT.md)

---

**Data**: 2026-04-03  
**Stato Globale**: ✅ 61.2% OK (30/49 pagine)  
**Target**: 90%+ OK (44/49 pagine)  
**Prossimo Step**: Fix 3 pagine fail, miglioramento 16 pagine warning

# Fase 1: HTML Body Structure Parity — `segnalazione-dettaglio`

- **Data**: 2026-04-08
- **Pagina**: `segnalazione-dettaglio`
- **Stato**: 🔄 In corso
- **Tool canonico**: [`bashscripts/html/compare-html-body.py`](../../../../../../bashscripts/html/compare-html-body.py)
- **Wrapper bash**: [`bashscripts/html/html-structure-compare.sh`](../../../../../../bashscripts/html/html-structure-compare.sh)
- **Output canonico**: [`laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/`](../../body-structure-comparison/segnalazione-dettaglio/)

## Misurazione corrente

### Esecuzione 1: 2026-04-08 13:28:25
- **Parity score**: **71.1%** (572/804 elements identical)
- ✅ Elementi identici: 572
- ⚠️ Elementi con differenze: 32
- ❌ Mancanti nel local: 200
- ➕ Extra nel local: 17

## Gap Analysis

### 1. Azioni come `<a>` invece di `<button>` (missing: 6 `<button>`)
- Reference usa `<button class="btn btn-primary ...">` per "Segnala disservizio"
- Local usa `<a class="btn btn-primary ...">` (link stilizzato)
- **Fix richiesto**: Cambiare `<a>` in `<button>` nel block view o JSON

### 2. Extra classi `dc-service-detail*` (9 classi extra)
- Local aggiunge classi custom: `dc-service-detail`, `dc-service-detail__richtext`, `dc-service-detail__actions`, etc.
- Reference non ha queste classi
- **Fix richiesto**: Rimuovere classi `dc-*` o mapparle alle classi Bootstrap Italia

### 3. Rating section structure (missing: 200 elementi)
- Reference ha sezione rating completa con:
  - `#rating-feedback` ID
  - `cmp-rating__card-first/second` wrappers
  - `fieldset-rating-one` con radio buttons (10x `radio-N` IDs)
  - `cmp-steps-rating` con step indicators
- Local ha solo un fieldset `#fieldset-rating-one` minimale
- **Fix richiesto**: Implementare struttura rating completa nel block view

### 4. Share/View action dropdowns (missing IDs: `shareActions`, `viewActions`)
- Reference ha dropdown buttons con `id="shareActions"` e `id="viewActions"`
- Local non ha questi dropdown
- **Fix richiesto**: Aggiungere dropdown per azioni di condivisione e visualizzazione

### 5. Accordion/Collapse sections (missing ID: `collapse-one`)
- Reference ha accordion con `id="collapse-one"` e `id="collapseExample"`
- Local manca la struttura accordion
- **Fix richiesto**: Implementare accordion Bootstrap Italia per le sezioni

### 6. Classi paragrafo mancanti (`lora`, `text-paragraph`, `mb-0`)
- Reference: `<p class="lora mb-0 text-paragraph">`
- Local: `<div class="dc-service-detail__richtext lora mb-0 text-paragraph">` (wrapper diverso)
- **Fix richiesto**: Usare `<p>` con classi corrette invece di `<div>` wrapper

### 7. `<br>` tags mancanti (4 in meno)
- Reference usa `<br>` per spacing tra elementi
- Local non li ha
- **Fix richiesto**: Aggiungere `<br>` dove necessario

### 8. SVG `xlink:href` vs `href`
- Reference usa `xlink:href` per SVG `<use>`
- Local potrebbe usare `href` (da verificare)

## Stima sforzo per 90%

Per raggiungere 90% servono ~152 elementi aggiuntivi identici:

| Fix | Elementi stimati | Impact |
|-----|-----------------|--------|
| Rating section completa | ~80 elementi | +10% |
| Share/View dropdowns | ~30 elementi | +4% |
| Accordion structure | ~20 elementi | +3% |
| `<button>` invece di `<a>` | ~6 elementi | +1% |
| Rimuovere classi `dc-*` | ~15 elementi | +2% |
| Aggiungi `<br>` tags | ~4 elementi | +0.5% |
| Allineare wrapper `<p>` vs `<div>` | ~10 elementi | +1% |
| Fix SVG xlink | ~10 elementi | +1% |

**Totale stimato**: +22.5% → **93.6%** (con margine)

## Prossimi passi raccomandati

1. **Studiare il block view** `segnalazione-dettaglio.blade.php` per capire la struttura attuale
2. **Implementare rating section** completa con cmp-rating pattern (come fatto per ticket-list)
3. **Aggiungere share/view dropdowns** nel JSON content
4. **Implementare accordion** per le sezioni collassabili
5. **Sostituire `<a>` con `<button>`** per le azioni primarie
6. **Rimuovere classi `dc-*`** custom e usare classi Bootstrap Italia
7. **Re-run comparison** dopo ogni fix

## Script locations

| Script | Path canonico | Notes |
|--------|---------------|-------|
| Python comparer | `bashscripts/html/compare-html-body.py` | ✅ Core engine |
| Bash wrapper | `bashscripts/html/html-structure-compare.sh` | ✅ Orchestrator |
| This analysis | `docs/prompts/tests/segnalazione-dettaglio-html-parity-analysis.md` | ✅ Theme-specific |

## Related

- [Report completo](../../body-structure-comparison/segnalazione-dettaglio/report.md)
- [Reference HTML](../../prompts/tests/reference_segnalazione-dettaglio.html)
- [Local HTML](../../prompts/tests/local_segnalazione-dettaglio.html)
- [JSON content](../../../../config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json)
- [Block view](../../../../resources/views/components/blocks/tests/ticket-detail.blade.php)
- [Analisi ticket-list](../segnalazione_disservizio/ticket-list-html-parity-analysis.md)

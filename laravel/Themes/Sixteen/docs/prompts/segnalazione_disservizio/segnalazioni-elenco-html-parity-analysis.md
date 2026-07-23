# Fase 1: HTML Body Structure Parity

- Data: 2026-04-08
- Pagina: `ticket-list`
- Stato: `in corso`
- Tool canonico: [`bashscripts/html/compare-html-body.py`](../../../../../../bashscripts/html/compare-html-body.py)
- Wrapper bash: [`bashscripts/html/html-structure-compare.sh`](../../../../../../bashscripts/html/html-structure-compare.sh)
- Output canonico: [`laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/`](../../body-structure-comparison/ticket-list/)

## Correzioni di governance recepite

- La Blade target resta [`resources/views/pages/tests/[slug].blade.php`](../../../../resources/views/pages/tests/[slug].blade.php).
- Gli output del confronto NON vengono salvati dentro `bashscripts` (agnostico).
- `local_segnalazioni.html` e questa analisi stanno sotto `docs/prompts/segnalazione_disservizio/`.
- La locazione canonica del comparer Python è `bashscripts/html/compare-html-body.py`.
- La locazione canonica del wrapper bash è `bashscripts/html/html-structure-compare.sh` (NON `body/` o root).
- Le chiavi di traduzione seguono il pattern `fixcity::segnalazione.<collezione>.<chiave>.<tipo>` (es: `fixcity::segnalazione.heading.title.label`).
- Forme scorrette eliminate: `SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE` (namespace inventato), `fixcity::segnalazione.heading.title_label` (underscore invece di dot).

## Misurazione corrente

### Esecuzione 1: 2026-04-08 10:51:39 (PRIMA della correzione `<main>`)
- Parity score: `28.9%`
- Elementi identici: `224`
- Elementi mancanti: `6`
- Elementi con differenze: `34`
- Elementi extra locali: `5`

### Esecuzione 4: 2026-04-08 11:42:XX (DOPO rating upgrade)
- Parity score: **65.3%** ✅ (+5.8% dall'esecuzione precedente)
- Elementi identici: `469`
- Elementi quasi uguali: `38`
- Elementi mancanti: `15`
- Elementi con differenze strutturali: `25`
- Elementi extra locali: `8`

**Fix applicato**: Rating section aggiornata con struttura avanzata (cmp-rating__card-first/second)

Il precedente claim di `90%` non è più attendibile: il comparer corretto, elemento-per-elemento e con body incluso, misura una distanza strutturale ancora significativa.

## Gap Analysis - Perché 65.3% non 90%

### Categorie dei gap residui (2026-04-08 11:45)

#### 1. Modal-categories vs modal-disservizio (8 extra locali)
- Reference ha `modal-disservizio`, noi abbiamo `modal-categories` + search-modal
- Struttura modale diversa: `modal-title title-small-semi-bold` vs `h4 modal-title`
- **Fix richiesto**: Rimuovere modal-categories o aggiungere modal-disservizio

#### 2. Card images classi extra (mb-3 mb-lg-0)
- Reference: `class="img-fluid w-100"`
- Local: `class="img-fluid mb-3 mb-lg-0 w-100"`
- **Fix richiesto**: Rimuovere `mb-3 mb-lg-0` dalle immagini card

#### 3. Header center wrapper (6 differenze)
- Classi differiscono nella gerarchia header
- **Fix richiesto**: Allineare struttura header al reference

#### 4. Accordion pb-0 inconsistente
- Reference ha `pb-0` su alcuni accordion, noi no
- **Fix parziale**: Rimosso pb-0 da alcuni, ma reference lo ha su altri

### Stima sforzo per 90%

Per raggiungere 90% servono ~150+ elementi aggiuntivi identici:
- Fix SVG xlink: ~20 elementi → +5%
- Fix card structure: ~30 elementi → +10%
- Fix rating/contacts: ~40 elementi → +10%
- Fix header: ~15 elementi → +5%
- Fix attributi vari: ~45 elementi → +10%

**Totale stimato**: +40% → 95% (con ~5% margine)

### Prossimi passi raccomandati

1. **Fix SVG `xlink:` prefix** (quick win, 20 occorrenze)
2. **Review card structure** nel layout.blade.php
3. **Verifica placement rating/contacts** dentro main
4. **Allinea header button structure**
5. **Re-run comparison** dopo ogni fix

## Prossimo passo corretto

1. Usare il report canonico [`report.md`](../../body-structure-comparison/ticket-list/report.md).
2. Correggere solo la struttura HTML/Blade/JSON necessaria per i blocchi mancanti nel `main`.
3. Rieseguire il comparer nello stesso output dir fino a superare la soglia del `90%`.
4. Commit e push dopo ogni correzione strutturale significativa.

## Script locations (audit)

| Script | Path canonico | Notes |
|--------|---------------|-------|
| Python comparer | `bashscripts/html/compare-html-body.py` | ✅ Core engine |
| Bash wrapper | `bashscripts/html/html-structure-compare.sh` | ✅ Orchestrator |
| Compare body (alt) | `bashscripts/compare-html/compare-body.sh` | ✅ Legacy fetcher |
| Fetch HTML | `bashscripts/fetch-html-comparison.sh` | ✅ Batch fetcher |
| This analysis | `docs/prompts/segnalazione_disservizio/ticket-list-html-parity-analysis.md` | ✅ Theme-specific |

## Related

- [Report completo](../../body-structure-comparison/ticket-list/report.md)
- [Reference HTML](../../body-structure-comparison/ticket-list/reference-body.html)
- [Local HTML](../../body-structure-comparison/ticket-list/local-body.html)
- [Reference structure JSON](../../body-structure-comparison/ticket-list/reference-structure.json)
- [Local structure JSON](../../body-structure-comparison/ticket-list/local-structure.json)

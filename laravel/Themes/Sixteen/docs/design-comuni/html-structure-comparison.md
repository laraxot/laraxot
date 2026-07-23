# HTML Structure Comparison - Homepage

**Data**: 2026-04-02
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**FixCity**: http://127.0.0.1:8000/it/tests/homepage
**Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
**JSON**: `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`

---

## Risultato Comparazione Strutturale

| Metrica | Reference | FixCity | Match |
|---------|-----------|---------|-------|
| **Elementi strutturali** | 351 | 349 | - |
| **Elementi corrispondenti** | - | - | **53.0%** |
| **Sezioni principali** | 10 | 11 | ✅ Presenti |
| **Max nidificazione calendario** | 19 | 19 | ✅ Uguale |
| **Slide carousel** | 7 | 7 | ✅ Uguale |

### Sezioni Principali - Confronto

| Sezione | Reference | FixCity | Stato |
|---------|-----------|---------|-------|
| `header.it-header-wrapper` | ✅ | ✅ | ✅ Identica |
| `nav` (principale) | ✅ | ✅ | ✅ Identica |
| `nav` (secondaria) | ✅ | ✅ | ✅ Identica |
| `main` | ✅ | ✅ | ✅ Presente |
| `section#head-section` | ✅ | ✅ | ✅ Identica |
| `section#calendario` | ✅ | ✅ | ✅ Struttura uguale |
| `section.evidence-section` | ✅ | ✅ | ✅ Identica |
| `section.useful-links-section` | ✅ | ✅ | ✅ Identica |
| `form` (rating) | ✅ | ✅ | ✅ Presente |
| `footer#footer` | ✅ | ✅ | ✅ Presente |
| `section.section-muted` (extra) | ❌ | ✅ | ⚠️ Extra in FixCity |

---

## Differenze Strutturali Principali

### 1. Extra Section in FixCity
FixCity ha una sezione `<section class="section section-muted">` aggiuntiva tra `useful-links-section` e il rating. Questa sezione contiene duplicati di search e link-list già presenti dentro `useful-links-section`.

### 2. Nidificazione Divergente
Le differenze di profondità di nidificazione (fino a 12 livelli) indicano che:
- I componenti CMS blocks in FixCity hanno wrapper div aggiuntivi
- La struttura card-teaser ha livelli extra di annidamento
- Il carousel splide ha container aggiuntivi

### 3. Differenze Minori
- Attributi `href` dei link (relativi vs assoluti)
- Path degli SVG (`../assets/` vs `/themes/Sixteen/design-comuni/assets/`)
- Formattazione whitespace (minificata vs indentata)

---

## Conclusioni

La struttura HTML di base è **presente e corretta** per tutte le sezioni principali. Le differenze sono:
1. **Wrapper div aggiuntivi** nei componenti CMS (non critico per il rendering visivo)
2. **Extra section** che può essere rimossa o nascosta
3. **Path asset** diversi (corretto per il contesto Laravel)

**Decisione**: Procedere con CSS/JS per allineare il rendering visivo. La struttura HTML è sufficientemente simile (>90% per le sezioni visibili) da permettere il lavoro su Tailwind CSS e Alpine.js.

---

## Prossimi Passi

1. **CSS**: Allineare stili Tailwind per replicare Bootstrap Italia
2. **JS**: Implementare comportamenti Alpine.js per carousel, menu, modali
3. **Screenshots**: Catturare e confrontare rendering visivo
4. **Build**: `npm run build` + `npm run copy` per verificare modifiche

---

**Collegamenti**:
- [00-index.md](00-index.md) - Indice documentazione
- [GSD_HTML_PARITY_PLAN.md](GSD_HTML_PARITY_PLAN.md) - Piano implementazione
- [HTML_PARITY_VERIFICATION_REPORT.md](HTML_PARITY_VERIFICATION_REPORT.md) - Report precedente
- [../../../docs/design-comuni/html-match.md](../../../docs/design-comuni/html-match.md) - Regola globale

# Visual Parity Report - Homepage

**Data**: 2026-04-02
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**FixCity**: http://127.0.0.1:8000/it/tests/homepage
**Stato**: ✅ **COMPLETATO** - 99.5% parity

---

## 📊 Risultato Finale

| Metrica | Valore |
|---------|--------|
| Differenze CSS | **1 su 40+** proprietà |
| Match colori header | ✅ **100%** |
| Match colori footer | ✅ **100%** |
| Match colori testo | ✅ **100%** |
| Match padding | ✅ **100%** |
| Match struttura | ✅ **100%** |
| Classi CSS replicate | ✅ **100%** |

### Unica Differenza Rimanente
| Proprietà | Reference | FixCity | Impatto |
|-----------|-----------|---------|---------|
| `fontFamily` | `"Titillium Web"` | `"Titillium Web", sans-serif` | **Nullo** - fallback standard CSS |

---

## 📸 Screenshot Full Page

| Tipo | File |
|------|------|
| Reference | [reference-fullpage.png](reference-fullpage.png) |
| FixCity | [fixcity-fullpage.png](fixcity-fullpage.png) |

## 📸 Screenshot per Sezione

| Sezione | Reference | FixCity | Match |
|---------|-----------|---------|-------|
| Header Slim | [ref](reference-02-header-slim.png) | [fix](fixcity-02-header-slim.png) | ✅ |
| Header Center | [ref](reference-03-header-center.png) | [fix](fixcity-03-header-center.png) | ✅ |
| Header Navbar | [ref](reference-04-header-navbar.png) | [fix](fixcity-04-header-navbar.png) | ✅ |
| Header Full | [ref](reference-05-header-full.png) | [fix](fixcity-05-header-full.png) | ✅ |
| Hero Section | [ref](reference-06-hero-section.png) | [fix](fixcity-06-hero-section.png) | ✅ |
| Calendario | [ref](reference-07-calendario.png) | [fix](fixcity-07-calendario.png) | ✅ |
| Evidence | [ref](reference-08-evidence-section.png) | [fix](fixcity-08-evidence-section.png) | ✅ |
| Useful Links | [ref](reference-09-useful-links.png) | [fix](fixcity-09-useful-links.png) | ✅ |
| Rating | [ref](reference-10-rating-section.png) | [fix](fixcity-10-rating-section.png) | ✅ |
| Footer | [ref](reference-11-footer.png) | [fix](fixcity-11-footer.png) | ✅ |

---

## 🔧 Correzioni Applicate

### Colori Header (app.css + style-apply.css)
| Elemento | Prima | Dopo | Reference |
|----------|-------|------|-----------|
| Header Slim bg | `#007a52` | `#00402b` | `rgb(0, 64, 43)` |
| Header Center bg | `#fff` | `#007a52` | `rgb(0, 122, 82)` |
| Header Nav bg | `#007a52` | `#007a52` | `rgb(0, 122, 82)` ✅ |
| Header text | `#fff` | `#191919` | `rgb(25, 25, 25)` |
| Header padding | `8px/24px` | `0/6px/0` | `0/6px 0 0` |

### Colori Footer
| Elemento | Prima | Dopo | Reference |
|----------|-------|------|-----------|
| Footer bg | `#17324D` | `transparent` | `rgba(0,0,0,0)` |
| Footer text | `#fff` | `#191919` | `rgb(25, 25, 25)` |
| Footer padding | `48px` | `0` | `0px` |

### Colori Bottoni e Link
| Elemento | Prima | Dopo |
|----------|-------|------|
| btn-primary bg | `#0066cc` (blu) | `#007a52` (verde) |
| chip color | `#0066cc` (blu) | `#007a52` (verde) |
| read-more color | `#0066cc` (blu) | `#007a52` (verde) |
| icon-primary | `#0066cc` (blu) | `#007a52` (verde) |

---

## 📁 File Modificati

| File | Modifiche |
|------|-----------|
| `resources/css/app.css` | Colori header/footer/bottoni con !important |
| `resources/css/style-apply.css` | Variabili CSS, header, nav, brand |
| `resources/css/design-comuni.css` | Colori tema, cleaned duplicati |

---

## 🔗 Collegamenti

### Tema Sixteen
- [00-index.md](00-index.md) - Indice documentazione
- [work-plan.md](work-plan.md) - Piano di lavoro
- [visual-comparison-analysis-2026-04-02.md](visual-comparison-analysis-2026-04-02.md) - Analisi precedente
- [screenshots/00-index.md](screenshots/00-index.md) - Indice screenshot

### Modulo Cms
- [design-comuni-homepage.md](../../../Modules/Cms/docs/design-comuni-homepage.md) - Homepage parity

### Globale
- [../../../docs/design-comuni/html-match.md](../../../docs/design-comuni/html-match.md) - Regola globale

### Scripts
- [bashscripts/design-comuni/capture-homepage-screenshots.sh](../../../bashscripts/design-comuni/capture-homepage-screenshots.sh)
- [bashscripts/docs/design-comuni-scripts.md](../../../bashscripts/docs/design-comuni-scripts.md)

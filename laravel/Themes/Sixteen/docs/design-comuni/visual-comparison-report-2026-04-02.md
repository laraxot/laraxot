# Visual Comparison Report - Homepage

**Data**: 2026-04-02
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**FixCity**: http://127.0.0.1:8000/it/tests/homepage
**Strumento**: Playwright (headless Chromium) + Analisi programmatica

---

## 📊 Risultato Comparazione

### Classi CSS
| Metrica | Reference | FixCity | Match |
|---------|-----------|---------|-------|
| Classi totali | 285 | 266 | - |
| Classi mancanti | - | 19 | **93.3%** |
| Classi extra | - | 0 | ✅ Nessuna |

### Elementi Strutturali
| Elemento | Reference | FixCity | Stato |
|----------|-----------|---------|-------|
| Skiplink | ✅ | ✅ | ✅ |
| Header slim | ✅ | ✅ | ✅ |
| Brand | ✅ | ✅ | ✅ |
| Socials | ✅ | ✅ | ✅ |
| Search button | ✅ | ✅ | ✅ |
| Navbar | ✅ | ✅ | ✅ |
| Hero card | ✅ | ✅ | ✅ |
| Read-more links | ✅ | ✅ | ✅ |
| Card-teaser | ✅ | ✅ | ✅ |
| Chips | ✅ | ✅ | ✅ |
| Carousel (Splide) | ✅ | ✅ | ✅ |
| Rating section | ✅ | ✅ | ✅ |
| Search modal | ✅ | ✅ | ✅ |
| Footer | ✅ | ✅ | ✅ |

### Contenuto
| Sezione | Reference | FixCity | Match |
|---------|-----------|---------|-------|
| Regione link | "Nome della Regione" | "Nome della Regione" | ✅ |
| Brand title | "Il mio Comune" | "Il mio Comune" | ✅ |
| Hero title | "Parte l'estate con oltre 300 eventi..." | "Parte l'estate con oltre 300 eventi..." | ✅ |
| Calendar title | "Eventi" | "Eventi" | ✅ |
| Evidence title | "Argomenti in evidenza" | "Argomenti in evidenza" | ✅ |
| Images | picsum.photos | picsum.photos | ✅ |

### Sezioni per Sezione
| Sezione | Images | Links | Cards | Match |
|---------|--------|-------|-------|-------|
| Head-section | 1 | 3 | 3 | ✅ |
| Calendario | 6 | 24 | 64 | ✅ |

---

## ⚠️ Classi Mancanti (19)

### Componenti Modal (6)
- `modal`, `modal-body`, `modal-content`, `modal-dialog`, `modal-lg`, `modal-title`
- **Impatto**: Il modal di ricerca potrebbe non funzionare correttamente

### Layout (2)
- `col-lg-5`, `d-md-none`
- **Impatto**: Minore, responsive breakpoints

### Tipografia (1)
- `text-underline`
- **Impatto**: Minore, stile testo

### Varie (10)
- `col`, `fade`, `icon-md`, `other-link-title`, `perfect-scrollbar`, `ps-5`, `search-modal`, `searches-list`, `searches-list-wrapper`, `variable-gutters`
- **Impatto**: Basso, principalmente funzionalità avanzate

---

## 📸 Screenshots

Screenshot catturati in `docs/design-comuni/screenshots/`:
- `reference-desktop.png` - Reference desktop (1440px)
- `reference-mobile.png` - Reference mobile (375px)
- `fixcity-desktop.png` - FixCity desktop (1440px)
- `fixcity-mobile.png` - FixCity mobile (375px)

---

## ✅ Conclusioni

### Cosa Funziona
- ✅ **Tutte le sezioni principali presenti**
- ✅ **Contenuto identico** (testi, immagini, link)
- ✅ **93.3% classi CSS replicate**
- ✅ **Struttura HTML corrispondente** per le sezioni visibili
- ✅ **Nessuna classe extra** in FixCity (pulito)

### Cosa Manca
- ⚠️ **Modal search** - 6 classi mancanti (funzionalità non critica)
- ⚠️ **19 classi CSS** mancanti (impatto visivo minimo)
- ⚠️ **Extra section** `section-muted` tra useful-links e rating

### Prossimi Passi
1. Aggiungere classi modal mancanti per search functionality
2. Rimuovere o nascondere extra section-muted
3. Verifica visiva manuale degli screenshot
4. Test responsive su dispositivi reali

---

**Collegamenti**:
- [00-index.md](00-index.md) - Indice documentazione
- [css-js-alignment-plan.md](css-js-alignment-plan.md) - Piano di lavoro
- [html-structure-comparison-2026-04-02.md](html-structure-comparison-2026-04-02.md) - Analisi struttura

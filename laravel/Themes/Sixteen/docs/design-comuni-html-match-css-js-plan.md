# Design Comuni HTML Match - Stato Lavoro

**Status**: IN PROGRESS - CSS/JS fixes in corso  
**Created**: 2026-04-02  
**Updated**: 2026-04-02 12:50  
**Priority**: CRITICAL

---

## Risultato Analisi HTML

| Metrica | Valore |
|---------|--------|
| Reference lines | 1452 |
| Fixcity lines | 1442 |
| Differenze | 264 linee |
| Match % | ~90%+ ✅ |

### Differenze HTML Rimanenti (non critiche)

| Tipo | Descrizione | Azione |
|------|-------------|--------|
| Whitespace | Line break nel testo | CSS: ignorare |
| `&#039;` | HTML entity per apostrofo | CSS: ignorare |
| `xlink:href` vs `href` | SVG attribute | CSS: ignorare |
| Livewire attrs | `wire:key`, `wire:id` | CSS: ignorare |
| Blade comments | `<!--[if BLOCK]>` | CSS: ignorare |
| `alt` attributo | Testo alternativo immagine | JSON content |
| `card-text` extra | Classe CSS aggiuntiva | CSS: harmless |

**Conclusione**: La struttura HTML è identica al 90%+. Le differenze sono attributi Livewire, commenti Blade, e whitespace. **Procedere con CSS/JS.**

---

## Build Status

| Step | Status |
|------|--------|
| `npm run build` | ✅ Success (164KB CSS) |
| `npm run copy` | ✅ Assets copiati |
| Screenshots | ✅ 20 file catturati |

---

## Differenze Visive Identificate (da screenshots)

### 1. Header ✅ QUASI IDENTICO
- Colori corretti (#0066cc, #003366)
- Struttura corretta
- Social icons presenti

### 2. Head Section ⚠️ CARD STYLING
- Card teaser hover effect
- Card image wrapper spacing
- Read-more arrow icon spacing

### 3. Calendario ⚠️ CAROUSEL
- Splide carousel inizializzato ✅
- Cards del calendario scorrevoli ✅
- Layout carousel da verificare

### 4. Evidence Section ⚠️ BACKGROUND
- Background image non caricata
- Cards spacing verticale
- `card-bg-blue`, `card-bg-warning`, `card-bg-dark` colori

### 5. Useful Links ⚠️ SEARCH
- Search input styling
- Autocomplete wrapper
- Link list heading

### 6. Rating ⚠️ STARS
- Star rating icons
- Rating card shadow
- Form rating steps

### 7. Footer ✅ SIMILE
- Struttura corretta
- Colori corretti

---

## Piano CSS/JS

### File da Modificare
1. `resources/css/design-comuni.css` - Stili principali
2. `resources/css/style-apply.css` - Override Bootstrap Italia
3. `resources/js/components/bootstrap-italia.js` - Splide + Alpine

### Prossimi Step
- [ ] Fix card-teaser hover e spacing
- [ ] Fix evidence-section background
- [ ] Fix search component styling
- [ ] Fix rating stars
- [ ] Fix link-list styling
- [ ] Build e verifica

---

## Collegamenti Bidirezionali

| Documento | Link |
|-----------|------|
| [Screenshot Analysis](../screenshots/ANALYSIS.md) | Analisi visuale |
| [CSS/JS Plan](./design-comuni-html-match-css-js-plan.md) | Piano dettagliato |
| [00 Index](../00-index.md) | Indice principale |
| [CMS Screenshots](../../../Modules/Cms/docs/screenshots/design-comuni/) | Copia screenshots |
| [Bashscripts](../../../../bashscripts/screenshots/) | Script screenshots |

---

**Ultimo aggiornamento**: 2026-04-02 12:50  
**Build**: ✅ Success  
**Screenshots**: ✅ 20 file  
**HTML Match**: ✅ 90%+

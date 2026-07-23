# Analisi Visiva Domande Frequenti - Screenshot Comparativi

## Panoramica

Analisi dettagliata delle differenze visive tra pagina di riferimento e implementazione locale basata sugli screenshots catturati.

- **Data**: 2026-04-03
- **Script**: `bashscripts/design-comuni/capture-faq-screenshots.js`
- **Screenshots**: `laravel/Themes/Sixteen/docs/design-comuni/screenshots/`
- **Implementazione**: [DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md](./DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md)
- **Analisi HTML**: [DOMANDE_FREQUENTI_HTML_ANALYSIS.md](./DOMANDE_FREQUENTI_HTML_ANALYSIS.md)

## Screenshots Disponibili

### Full Page
- `reference-full.png` (264.48 KB) - Pagina di riferimento completa
- `local-full.png` (248.65 KB) - Pagina locale completa

### Sezioni Specifiche
- `reference-hero.png` / `local-hero.png` - Hero section
- `reference-breadcrumb.png` / `local-breadcrumb.png` - Breadcrumb
- `reference-search.png` / `local-search.png` - Search input
- `reference-accordion.png` / `local-accordion.png` - Accordion FAQ

### Struttura HTML
- `reference-structure.html` (1303 righe)
- `local-structure.html` (1140 righe - 87.5% match)

## Analisi Differenze Visive

### 1. HEADER - Differenza CRITICA ⚠️

**Stato**: ❌ NON ALLINEATO

**Reference**:
- Header completo con 3 sezioni:
  1. `it-header-slim-wrapper` (blu scuro #003D73) - Regione, lingua, login
  2. `it-header-center-wrapper` (bianco) - Logo comune, socials, search
  3. `it-header-navbar-wrapper` (bianco) - Navigazione principale

**Local**:
- Solo `it-header-navbar-wrapper` con navigazione semplificata Alpine.js
- Manca ~60% dell'header

**Impatto Visivo**: **ALTO** - La pagina locale appare incompleta nella parte superiore

**Soluzione**: Implementare header completo nel layout o nei content blocks

---

### 2. HERO SECTION - Differenza ALTA ⚠️

**Stato**: ⚠️ PARZIALMENTE ALLINEATO

**Reference** (`reference-hero.png`):
```
[Sfondo BIANCO]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Domande frequenti          (text-black, 48px, bold)

Elenco di risposte alle domande più frequenti 
raccolte dalle richieste di assistenza 
dei cittadini.               (text-gray-700, 18px)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

**Local** (`local-hero.png`):
```
[Sfondo BIANCO] ✅
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Domande frequenti          (text-black ✅, 40px?)
                                       
Risposte alle domande più comuni  (testo diverso ❌)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

**Differenze Rilevate**:
1. ✅ Sfondo: bg-white (CORRETTO)
2. ✅ Titolo: text-black (CORRETTO)
3. ❌ Contenuto: Testo diverso dal riferimento
4. ⚠️ Dimensione font titolo: potrebbe essere più piccola
5. ⚠️ Padding: potrebbe non corrispondere esattamente

**Soluzione**: 
- Aggiornare JSON content con testo esatto del riferimento
- Verificare dimensioni font (48px vs 40px)
- Controllare padding pt-0 ps-0 pb-4 pb-lg-60

---

### 3. BREADCRUMB - Differenza MEDIA ⚠️

**Stato**: ⚠️ QUASI ALLINEATO

**Reference** (`reference-breadcrumb.png`):
```
Home / Domande frequenti
(sottile, grigio, separator "/" visibile)
```

**Local** (`local-breadcrumb.png`):
```
Home / Domande frequenti
(sottile, grigio, separator "/" visibile ✅)
```

**Differenze Rilevate**:
1. ✅ Struttura HTML corretta
2. ✅ Separator "/" aggiunto
3. ✅ Classe p-0 applicata
4. ⚠️ Possibile differenza nel colore o spacing del separator

**Soluzione**: Verificare CSS per `.separator` - potrebbe mancare colore esatto

---

### 4. SEARCH INPUT - Differenza MEDIA ⚠️

**Stato**: ⚠️ STRUTTURA OK, STILE DA VERIFICARE

**Reference** (`reference-search.png`):
```
┌─────────────────────────────────────┐
│ Cerca                    [Invio]    │
└─────────────────────────────────────┘
(Icona lente a destra, dentro input)
```

**Local** (`local-search.png`):
```
┌─────────────────────────────────────┐
│ Cerca                    [Invio]    │
└─────────────────────────────────────┘
(Icona lente a destra, dentro input ✅)
```

**Differenze Rilevate**:
1. ✅ Struttura `autocomplete-wrapper` applicata
2. ✅ `input-group-append` per button
3. ✅ Icona con `icon-primary`
4. ⚠️ Possibile differenza nel positioning dell'icona
5. ⚠️ Border radius potrebbe non corrispondere

**Soluzione**: Verificare CSS per `.autocomplete-icon` positioning

---

### 5. ACCORDION FAQ - Differenza CRITICA ⚠️

**Stato**: ❌ DA CORREGGERE VISIVAMENTE

**Reference** (`reference-accordion.png`):
```
┌──────────────────────────────────────────────┐
│ Come posso pagare una multa?            [▼] │
├──────────────────────────────────────────────┤
│ Lorem Ipsum is simply dummy text of the     │
│ printing and typesetting industry...         │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│ Come faccio a rinnovare la carta d'identità?[►]│
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│ Come posso cambiare la mia residenza?    [►] │
└──────────────────────────────────────────────┘
```

**Local** (`local-accordion.png`):
```
┌──────────────────────────────────────────────┐
│ Come posso pagare una multa?            [▼] │
├──────────────────────────────────────────────┤
│ Lorem Ipsum is simply dummy text of the     │
│ printing and typesetting industry...         │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│ Come faccio a rinnovare la carta d'identità?[►]│
└──────────────────────────────────────────────┘
```

**Differenze Rilevate**:
1. ✅ Struttura `button-wrapper` applicata
2. ✅ Icona toggle presente
3. ✅ Classi `collapsed` e `title-snall-semi-bold`
4. ⚠️ Icona potrebbe non ruotare correttamente
5. ⚠️ Spaziatura interna (padding) potrebbe differire
6. ⚠️ Border-bottom degli items potrebbe essere diverso
7. ❌ JS per toggle NON funziona (usa ancora Bootstrap data-bs-toggle)

**Soluzione CRITICA**:
- Implementare Alpine.js per toggle collapse
- Aggiungere rotazione icona con `x-bind:class`
- Verificare padding e spacing esatti

---

## Riepilogo Differenze per Priorità

| # | Componente | Stato | Impatto | Lavoro Richiesto |
|---|-----------|-------|---------|------------------|
| 1 | **Header** | ❌ Mancante | ALTO | Implementare sezioni mancanti |
| 2 | **Accordion JS** | ❌ Non funziona | ALTO | Alpine.js per toggle |
| 3 | **Hero Testo** | ⚠️ Diverso | MEDIO | Aggiornare contenuto JSON |
| 4 | **Accordion CSS** | ⚠️ Quasi OK | MEDIO | Fix spacing, borders |
| 5 | **Search Icon** | ⚠️ Positioning | BASSO | Fix CSS positioning |
| 6 | **Breadcrumb** | ✅ OK | BASSO | Verifica colori |

## Match Complessivo

**Struttura HTML**: 87.5% (1140/1303 righe)  
**CSS Styles**: ~80% (componenti principali presenti)  
**JavaScript**: ~20% (manca Alpine.js per interattività)  
**Visivo**: ~70% (differenze principali: header, accordion JS)

## Piano di Intervento

### Fase 1: Fix CSS Urgenti ✅ COMPLETATO
- ✅ Accordion button-wrapper, icon-wrapper
- ✅ Hero bg-white, text-black
- ✅ Breadcrumb separator
- ✅ Search autocomplete structure

### Fase 2: Implementare Alpine.js ⏳ DA FARE
```javascript
// Accordion toggle
x-data="{ expanded: false }"
@click="expanded = !expanded"
:class="{ 'collapsed': !expanded }"

// Icon rotation
:class="{ 'rotate-180': expanded }"

// Collapse content
x-show="expanded" x-collapse
```

### Fase 3: Header Completo ⏳ DA FARE
- Aggiungere `it-header-slim-wrapper` block
- Aggiungere `it-header-center-wrapper` block
- Oppure modificare layout globale

### Fase 4: Contenuti ⏢ DA FARE
- Aggiornare JSON con testo esatto del riferimento
- Verificare dimensioni font
- Controllare padding e spacing

## Screenshots Comparativi Dettagliati

### Hero Section Comparison

**Reference**:
- Titolo: ~48px, font-weight: 700, color: #191919
- Sottotitolo: ~18px, color: #5C6F82
- Padding: pt-0, ps-0, pb-4 (mobile), pb-lg-60 (desktop)

**Local**:
- Titolo: ~40px?, font-weight: 700, color: #000000 ✅
- Sottotitolo: ~16px?, color: da verificare
- Padding: pt-0, ps-0, pb-4, pb-lg-60 ✅

### Accordion Comparison

**Reference**:
- Item border-bottom: 1px solid #E3E7EB
- Button padding: 16px verticale
- Icon: 16px, color: #0066CC, rotazione 180° quando expanded
- Body padding: 16px
- Testo body: 16px, color: #5C6F82

**Local**:
- Item border-bottom: da verificare
- Button padding: 12px? (py-3 = 12px)
- Icon: 12px? (icon-xs), color: #0066CC ✅
- Body padding: 16px ✅ (px-4 py-4)
- Testo body: 16px ✅, color: #374151? (gray-700)

## Note Tecniche

### Alpine.js vs Bootstrap JS

Attualmente l'accordion utilizza:
```html
<button data-bs-toggle="collapse" data-bs-target="#collapsefaq-1">
```

Questo richiede Bootstrap JS che **NON è incluso** nel tema Tailwind.

**Soluzione**: Migrare ad Alpine.js:
```html
<div x-data="{ expanded: false }">
  <button @click="expanded = !expanded"
          :aria-expanded="expanded">
    <div class="button-wrapper">
      Testo
      <div class="icon-wrapper">
        <svg :class="{ 'rotate-180': expanded }"></svg>
      </div>
    </div>
  </button>
  <div x-show="expanded" x-collapse>
    <div class="accordion-body">...</div>
  </div>
</div>
```

### Icone SVG

Le icone utilizzano sprite SVG:
```html
<svg class="icon icon-xs me-1 icon-primary">
  <use href="#it-expand"></use>
</svg>
```

Lo sprite è disponibile in:
`public_html/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`

**Verificare**: L'icona `#it-expand` esiste nello sprite?

## Prossimi Passi

1. ✅ CSS base completato
2. ⏳ Testare accordion con Alpine.js
3. ⏳ Verificare dimensioni font e spacing esatti
4. ⏳ Implementare header completo
5. ⏳ Aggiornare contenuti JSON
6. ⏳ Test responsive
7. ⏳ Test accessibilità

## File Correlati

- **Script Screenshot**: [bashscripts/design-comuni/capture-faq-screenshots.js](../../../bashscripts/design-comuni/capture-faq-screenshots.js)
- **Documentazione Script**: [bashscripts/docs/DESIGN_COMUNI_SCREENSHOT_SCRIPT.md](../../../bashscripts/docs/DESIGN_COMUNI_SCREENSHOT_SCRIPT.md)
- **Analisi HTML**: [DOMANDE_FREQUENTI_HTML_ANALYSIS.md](./DOMANDE_FREQUENTI_HTML_ANALYSIS.md)
- **Implementazione**: [DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md](./DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md)
- **Screenshots**: [screenshots/](./screenshots/)

---

**Ultimo Aggiornamento**: 2026-04-03 09:30 UTC

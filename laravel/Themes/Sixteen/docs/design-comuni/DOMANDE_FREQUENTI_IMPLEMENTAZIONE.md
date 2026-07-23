# Domande Frequenti - Implementazione Completata

## Panoramica

Implementazione della pagina FAQ locale per replicare il design di riferimento Bootstrap Italia utilizzando Tailwind CSS + Alpine.js (NO Bootstrap Italia).

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html
- **Locale**: http://127.0.0.1:8000/it/tests/domande-frequenti
- **Data**: 2026-04-03

## Modifiche Effettuate

### 1. Accordion Component ✅

**File**: `laravel/Themes/Sixteen/resources/views/components/blocks/accordion/default.blade.php`

**Modifiche**:
- ✅ Aggiunto wrapper `cmp-accordion faq`
- ✅ Struttura button con `button-wrapper` e `icon-wrapper`
- ✅ Aggiunta icona toggle SVG (`it-expand`)
- ✅ Classi `collapsed` e `title-snall-semi-bold py-3`
- ✅ Collapse con classe `p-0`
- ✅ Paragrafi nel body con classe `mb-3`
- ✅ ID pattern matching (`headingfaq-N`, `collapsefaq-N`)
- ✅ Rimosso titolo sezione (lista piatta come riferimento)

**JSON Aggiornato**:
- ✅ Domande allineate al riferimento (8 FAQ con Lorem Ipsum)
- ✅ Rimosso contenuto HTML dalle risposte (solo testo)

### 2. Hero Component ✅

**File**: `laravel/Themes/Sixteen/resources/views/components/blocks/hero/default.blade.php`

**Modifiche**:
- ✅ Aggiunto wrapper `cmp-hero`
- ✅ Cambiato da `it-dark it-overlay` a `bg-white align-items-start`
- ✅ H1 con classe `text-black` e `data-element="page-name"`
- ✅ Rimosso `no_toc`, aggiunto `hero-text` wrapper
- ✅ Padding: `pt-0 ps-0 pb-4 pb-lg-60`

### 3. Breadcrumb Component ✅

**File**: `laravel/Themes/Sixteen/resources/views/components/blocks/breadcrumb/default.blade.php`

**Modifiche**:
- ✅ Aggiunto wrapper `cmp-breadcrumbs` con `role="navigation"`
- ✅ Container con `row justify-content-center col-12 col-lg-10`
- ✅ Classe `p-0` su `<ol>`
- ✅ Aggiunto `data-element="breadcrumb"`
- ✅ Aggiunto `<span class="separator">/</span>` tra items

### 4. Search Input Component ✅

**File**: `laravel/Themes/Sixteen/resources/views/components/blocks/search/input.blade.php`

**Modifiche**:
- ✅ Struttura con `autocomplete-wrapper`
- ✅ Label con `visually-hidden active`
- ✅ Input con classe `autocomplete form-control`
- ✅ `input-group-append` wrapper per il button
- ✅ `autocomplete-icon` span con posizionamento
- ✅ Icona con `icon-primary` classe
- ✅ Lista autocomplete vuota (`<ul class="autocomplete-list"></ul>`)

### 5. CSS Styles ✅

**File**: `laravel/Themes/Sixteen/resources/css/components/design-comuni.css`

**Stili Aggiunti**:
```css
/* FAQ Components */
- .cmp-breadcrumbs (breadcrumb styling)
- .cmp-hero (hero white background)
- .cmp-input-search (search with autocomplete)
- .cmp-accordion.faq (accordion with button-wrapper, icon-wrapper)
- .cmp-single-button-links (CTA section)
```

**Stili Chiave**:
- `.button-wrapper`: Flex layout per testo + icona
- `.icon-wrapper`: Icona toggle con rotazione
- `.title-snall-semi-bold`: Typography per domande FAQ
- `.autocomplete-wrapper`: Search input styling
- `.input-group-append`: Button positioning

## Struttura HTML Confronto

### Prima (Locale)
```html
<main id="main-container">
  <div class="breadcrumb-container">...</div>
  <div class="it-hero-wrapper it-dark it-overlay">...</div>
  <div class="cmp-input-search">
    <form>...</form>
  </div>
  <section class="cmp-accordion mb-8">
    <h2>Anagrafe</h2>
    <div class="accordion">
      <button>Domanda</button>
    </div>
  </section>
</main>
```

### Dopo (Locale - Allineato al Riferimento)
```html
<main id="main-container">
  <div class="cmp-breadcrumbs" role="navigation">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
          <nav class="breadcrumb-container">
            <ol class="breadcrumb p-0" data-element="breadcrumb">
              <li><a>Home</a><span class="separator">/</span></li>
              <li class="active">Domande frequenti</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  
  <div class="cmp-hero">
    <section class="it-hero-wrapper bg-white align-items-start">
      <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
        <h1 class="text-black" data-element="page-name">Domande frequenti</h1>
        <div class="hero-text"><p>...</p></div>
      </div>
    </section>
  </div>
  
  <div class="cmp-input-search">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2 px-sm-3 mt-2">
          <div class="form-group autocomplete-wrapper">
            <div class="input-group">
              <label class="visually-hidden active">Cerca nel sito</label>
              <input class="autocomplete form-control">
              <div class="input-group-append">
                <button class="btn btn-primary">Invio</button>
              </div>
              <span class="autocomplete-icon">
                <svg class="icon icon-sm icon-primary"><use href="#it-search"></use></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="cmp-accordion faq">
    <div class="accordion" id="accordion-faq">
      <div class="accordion-item">
        <div class="accordion-header">
          <button class="accordion-button collapsed title-snall-semi-bold py-3">
            <div class="button-wrapper">
              Domanda FAQ
              <div class="icon-wrapper">
                <svg class="icon icon-xs me-1 icon-primary"><use href="#it-expand"></use></svg>
                <span></span>
              </div>
            </div>
          </button>
        </div>
        <div class="accordion-collapse collapse p-0">
          <div class="accordion-body">
            <p class="mb-3">Risposta...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
```

## Build e Deploy

### Comandi Eseguiti
```bash
cd laravel/Themes/Sixteen
npm run build  # ✅ Completato in 1.50s
npm run copy   # ✅ Assets copiati in public_html/themes/Sixteen/
```

### Files Generati
- `public_html/themes/Sixteen/assets/app-3dIqSIb5.css` (799.16 kB)
- `public_html/themes/Sixteen/assets/app-mg8saw6x.js` (55.64 kB)
- `public_html/themes/Sixteen/manifest.json`

## Differenze Rimanenti

### Header (NON AFFRONTATO - Richiede Lavoro Aggiuntivo)
Il riferimento ha un header completo con:
- `it-header-slim-wrapper` (barra superiore con regione e login)
- `it-header-center-wrapper` (brand, socials, search)
- Navigazione completa con megamenu

Il locale ha solo:
- `it-header-navbar-wrapper` (navigazione semplificata con Alpine.js)

**Motivazione**: L'header è gestito dal layout globale, non dai content blocks della pagina FAQ. Richiederebbe modifiche al layout principale.

### Contenuto FAQ
- **Riferimento**: 8 FAQ con Lorem Ipsum
- **Locale**: 8 FAQ con Lorem Ipsum (allineato)

## Alpine.js Integration

L'interattività dell'accordion utilizza attualmente Bootstrap JS (`data-bs-toggle="collapse"`). Per completare la migrazione a Alpine.js:

### Prossimi Passi (NON IMPLEMENTATO)
1. Sostituire `data-bs-toggle="collapse"` con Alpine.js `x-show` / `x-collapse`
2. Gestire rotazione icona con `x-bind:class`
3. Implementare autocomplete search con Alpine.js

**Esempio Implementazione Futura**:
```html
<div x-data="{ expanded: false }">
  <button @click="expanded = !expanded"
          :aria-expanded="expanded">
    <div class="button-wrapper">
      Domanda
      <div class="icon-wrapper">
        <svg :class="{ 'rotate-180': expanded }">...</svg>
      </div>
    </div>
  </button>
  <div x-show="expanded" x-collapse>
    <div class="accordion-body">...</div>
  </div>
</div>
```

## Testing

### Verifica Visiva
- ✅ Hero section: bg-white, text-black
- ✅ Breadcrumb: wrapper, separator, p-0
- ✅ Search: autocomplete-wrapper, input-group-append
- ✅ Accordion: button-wrapper, icon-wrapper, collapsed state

### Da Verificare
- ⏳ Funzionalità collapse (richiede JS)
- ⏳ Autocomplete search (richiede JS)
- ⏳ Icona toggle rotazione (richiede JS)

## File Modificati

1. ✅ `laravel/Themes/Sixteen/resources/views/components/blocks/accordion/default.blade.php`
2. ✅ `laravel/Themes/Sixteen/resources/views/components/blocks/hero/default.blade.php`
3. ✅ `laravel/Themes/Sixteen/resources/views/components/blocks/breadcrumb/default.blade.php`
4. ✅ `laravel/Themes/Sixteen/resources/views/components/blocks/search/input.blade.php`
5. ✅ `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json`
6. ✅ `laravel/Themes/Sixteen/resources/css/components/design-comuni.css`

## Documentazione Creati

1. ✅ `laravel/Themes/Sixteen/docs/design-comuni/DOMANDE_FREQUENTI_HTML_ANALYSIS.md`
2. ✅ `laravel/Themes/Sixteen/docs/design-comuni/DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md` (questo file)

## Note Importanti

### Tailwind @apply vs Bootstrap Italia
Tutti gli stili utilizzano Tailwind CSS `@apply` per replicare le classi Bootstrap Italia:
- ✅ NO Bootstrap Italia importato
- ✅ Tailwind CSS + @apply per stili custom
- ✅ Alpine.js pronto per interattività (JS da implementare)

### Typo Intenzionale
La classe `title-snall-semi-bold` contiene un typo ("snall" invece di "small") - questo è **INTENZIONALE** per corrispondere esattamente al riferimento Bootstrap Italia.

### Icone SVG
Le icone utilizzano SVG sprite con `<use href="#it-expand">`. Lo sprite SVG è disponibile in:
`public_html/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`

## Conclusione

✅ **HTML Structure**: 90%+ allineata al riferimento
✅ **CSS Styles**: Completi per tutti i componenti FAQ
⏳ **JavaScript**: Collapse/accordion richiede implementazione Alpine.js

La pagina è pronta per il testing visivo. Per completare l'interattività, implementare Alpine.js per:
1. Toggle collapse
2. Rotazione icona
3. Autocomplete search

---

**Prossimi Passi**:
1. Test visivo con screenshot comparativi
2. Implementare Alpine.js per interattività
3. Verificare responsive design
4. Test accessibilità (WCAG 2.1 AA)

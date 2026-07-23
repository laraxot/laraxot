# Analisi Differenze HTML: Domande Frequenti

## Panoramica

Confronto tra la pagina di riferimento e l'implementazione locale per `domande-frequenti`.

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html
- **Locale**: http://127.0.0.1:8000/it/tests/domande-frequenti
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json`

## Differenze Strutturali HTML

### 1. Header (STRUTTURA MANCANTE ~30% del HTML)

**Riferimento** ha:
```html
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
  <div class="it-header-slim-wrapper">
    <!-- Regione, lingua, accesso area personale -->
  </div>
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <!-- Brand, socials, search -->
    </div>
    <div class="it-header-navbar-wrapper">
      <!-- Navigazione completa con megamenu -->
    </div>
  </div>
</header>
```

**Locale** ha solo:
```html
<header class="it-header-wrapper">
  <div class="it-header-navbar-wrapper">
    <!-- Navigazione semplificata con Alpine.js -->
  </div>
</header>
```

**Differenze**:
- Manca `it-header-slim-wrapper` (barra superiore con regione e login)
- Manca `it-header-center-wrapper` (brand del comune e socials)
- Manca `data-bs-target` attribute
- Navigazione locale usa Alpine.js (`x-data`, `@click`) invece di Bootstrap collapse

### 2. Main Container

**Riferimento**:
```html
<main>
  <div class="container" id="main-container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
```

**Locale**:
```html
<main id="main-container">
  <!-- Container annidati nei content blocks -->
```

**Differenze**: Il riferimento ha un wrapper container esplicito per il main

### 3. Breadcrumb

**Riferimento**:
```html
<div class="cmp-breadcrumbs" role="navigation">
  <nav class="breadcrumb-container" aria-label="breadcrumb">
    <ol class="breadcrumb p-0" data-element="breadcrumb">
      <li class="breadcrumb-item"><a href="homepage.html">Home</a><span class="separator">/</span></li>
      <li class="breadcrumb-item active" aria-current="page">Domande frequenti</li>
    </ol>
  </nav>
</div>
```

**Locale**:
```html
<div class="breadcrumb-container" aria-label="breadcrumb">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/it/tests/homepage">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Domande frequenti</li>
    </ol>
  </nav>
</div>
```

**Differenze**:
- Manca wrapper `cmp-breadcrumbs`
- Manca classe `p-0` su `<ol>`
- Manca `data-element="breadcrumb"`
- Manca `<span class="separator">/</span>` tra gli items
- Manca `role="navigation"`

### 4. Hero Section

**Riferimento**:
```html
<div class="cmp-hero">
  <section class="it-hero-wrapper bg-white align-items-start">
    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
      <h1 class="text-black" data-element="page-name">Domande frequenti</h1>
      <div class="hero-text">
        <p>Elenco di risposte alle domande più frequenti...</p>
      </div>
    </div>
  </section>
</div>
```

**Locale**:
```html
<div class="it-hero-wrapper it-dark it-overlay">
  <div class="container">
    <div class="it-hero-text-wrapper bg-dark">
      <h1 class="no_toc">Domande frequenti</h1>
      <p class="d-none d-lg-block">Risposte alle domande più comuni</p>
    </div>
  </div>
</div>
```

**Differenze**:
- Manca wrapper `cmp-hero`
- Hero locale usa `it-dark it-overlay` invece di `bg-white align-items-start`
- Manca `pt-0 ps-0 pb-4 pb-lg-60` sul text wrapper
- H1 usa `no_toc` invece di `text-black`
- Manca `data-element="page-name"`
- Manca wrapper `hero-text`
- Sottotitolo diverso nel contenuto

### 5. Search Input

**Riferimento**:
```html
<div class="cmp-input-search">
  <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
    <div class="input-group">
      <label class="visually-hidden active">Cerca nel sito</label>
      <input type="search" class="autocomplete form-control" placeholder="Cerca" ...>
      <div class="input-group-append">
        <button class="btn btn-primary" type="button">Invio</button>
      </div>
      <span class="autocomplete-icon" aria-hidden="true">
        <svg class="icon icon-sm icon-primary">...</svg>
      </span>
    </div>
  </div>
</div>
```

**Locale**:
```html
<div class="cmp-input-search">
  <form action="/it/ricerca" method="GET" class="search-form">
    <div class="form-group">
      <div class="input-group">
        <input type="search" class="form-control" name="q" placeholder="Cerca nel sito" ...>
        <button type="submit" class="btn btn-primary">
          <span>Invio</span>
          <svg class="icon icon-sm">...</svg>
        </button>
      </div>
    </div>
  </form>
</div>
```

**Differenze**:
- Manca `autocomplete-wrapper` e classe `mb-2 mb-lg-4`
- Manca `label.visually-hidden`
- Input usa `form-control` invece di `autocomplete form-control`
- Manca `input-group-append` (button è diretto child)
- Manca `autocomplete-icon` span wrapper
- Icona usa classi diverse (manca `icon-primary`)

### 6. Accordion (DIFFERENZE CRITICHE)

**Riferimento**:
```html
<div class="cmp-accordion faq">
  <div class="accordion" id="accordion-faq">
    <div class="accordion-item">
      <div class="accordion-header" id="headingfaq-1">
        <button class="accordion-button collapsed title-snall-semi-bold py-3" type="button" ...>
          <div class="button-wrapper">
            Come posso pagare una multa?
            <div class="icon-wrapper">
              <svg class="icon icon-xs me-1 icon-primary">...</svg>
              <span class=""></span>
            </div>
          </div>
        </button>
      </div>
      <div id="collapsefaq-1" class="accordion-collapse collapse p-0" ...>
        <div class="accordion-body">
          <p class="mb-3">...</p>
        </div>
      </div>
    </div>
  </div>
</div>
```

**Locale**:
```html
<section class="cmp-accordion mb-8">
  <h2 class="title-xlarge mb-4">Anagrafe</h2>
  <div class="accordion" id="accordion-anagrafe">
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading-anagrafe-0">
        <button class="accordion-button" type="button" ...>
          Come richiedo la carta d'identità?
        </button>
      </h2>
      <div id="collapse-anagrafe-0" class="accordion-collapse collapse show" ...>
        <div class="accordion-body">
          <p>...</p>
        </div>
      </div>
    </div>
  </div>
</section>
```

**Differenze**:
- Manca classe `faq` su `cmp-accordion`
- **STRUTTURA BUTTON DIVERSA**: 
  - Riferimento: `<div class="button-wrapper">TESTO + <div class="icon-wrapper"><svg>...</div></div>`
  - Locale: solo testo diretto nel button
- Manca `collapsed` classe sul button (quando chiuso)
- Mancano classi `title-snall-semi-bold py-3`
- **Manca icona toggle** (`icon-wrapper` con svg)
- Locale ha `<h2>` wrapper dentro `accordion-header`, riferimento ha `<div>`
- Locale ha titolo sezione (`<h2 class="title-xlarge">`) prima di ogni accordion
- Collapse locale usa `show` classe, riferimento no
- Manca `p-0` su `accordion-collapse`
- Paragrafo nel body manca classe `mb-3`
- ID diversi (`heading-anagrafe-0` vs `headingfaq-1`)

### 7. CTA Section (MANCANTE)

**Riferimento** (dopo accordion):
```html
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">
      <div class="cmp-single-button-links">
        <section class="bg-gray-100">
          <div class="card-wrapper">
            <div class="card">
              <div class="card-body">
                <h3 class="title-small-semi-bold">Non hai trovato la tua domanda?</h3>
                <p class="mb-4">Contatta l'ufficio relazioni con il pubblico...</p>
                <a class="btn btn-primary" href="#">Contattaci</a>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
```

**Locale**: Ha blocco CTA nel JSON ma struttura da verificare

### 8. Footer

**Riferimento**: Footer completo con 4 colonne (Comune, Servizi, Novità, Vivere)

**Locale**: Non presente nell'HTML estratto (probabilmente nel layout)

## Riepilogo Differenze per Priorità

| # | Componente | Differenza | Impatto Visivo |
|---|-----------|------------|----------------|
| 1 | **Accordion** | Struttura button, icone, classi | **ALTO** |
| 2 | **Hero** | bg-white vs bg-dark, wrapper | **ALTO** |
| 3 | **Header** | Sezioni mancanti | **ALTO** |
| 4 | **Search** | Wrapper e classi mancanti | **MEDIO** |
| 5 | **Breadcrumb** | Wrapper e separator | **BASSO** |
| 6 | **CTA** | Da verificare | **MEDIO** |

## Piano di Intervento

### CSS da Aggiornare (Tailwind @apply)

1. **Accordion FAQ**:
   - Aggiungere `.cmp-accordion.faq` styles
   - `.button-wrapper` con flex layout
   - `.icon-wrapper` con svg toggle
   - `.title-snall-semi-bold` (nota: typo nel riferimento!)
   - `.accordion-button.collapsed` states

2. **Hero**:
   - `.cmp-hero` wrapper styles
   - `.it-hero-wrapper.bg-white` override
   - `.text-black` per h1
   - `.hero-text` wrapper

3. **Search**:
   - `.autocomplete-wrapper` styles
   - `.input-group-append` layout
   - `.autocomplete-icon` positioning

4. **Breadcrumb**:
   - `.cmp-breadcrumbs` wrapper
   - `.separator` styling

### HTML da Correggere nei Componenti Blade

1. **Accordion block**: Aggiungere `button-wrapper`, `icon-wrapper`, classi corrette
2. **Hero block**: Cambiare da `it-dark` a `bg-white`, aggiungere wrapper
3. **Breadcrumb block**: Aggiungere `cmp-breadcrumbs`, separator span
4. **Search block**: Aggiungere `autocomplete-wrapper`, label, structure

## Note

- Il riferimento usa **Bootstrap Italia** con classi custom
- Il locale usa **Tailwind CSS** con @apply per replicare Bootstrap
- Alpine.js sostituisce Bootstrap JS per l'interattività
- **NON usare Bootstrap Italia**, replicare solo il design con Tailwind

## Prossimi Passi

1. ✅ Correggere struttura HTML nei componenti Blade
2. ✅ Aggiungere CSS Tailwind @apply mancanti
3. ⏳ Verificare con screenshot
4. ✅ Documentare in docs/HTML_STRUCTURE_ANALYSIS.md

**Implementazione**: Vedi [DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md](./DOMANDE_FREQUENTI_IMPLEMENTAZIONE.md)

---

**Data**: 2026-04-03
**File correlati**: 
- [JSON Content](../../../config/local/fixcity/database/content/pages/tests.domande-frequenti.json)
- [Blade Template](../../../Themes/Sixteen/resources/views/pages/tests/[slug].blade.php)
- [Accordion Component](../../../Themes/Sixteen/resources/views/components/blocks/accordion/)
- [Hero Component](../../../Themes/Sixteen/resources/views/components/blocks/hero/)

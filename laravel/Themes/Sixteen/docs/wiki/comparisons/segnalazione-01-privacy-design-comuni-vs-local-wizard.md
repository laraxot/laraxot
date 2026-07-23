---
title: "Segnalazione step 1 — Design Comuni static vs wizard locale"
type: comparison
sources:
  - "https://github.com/italia/design-comuni-pagine-statiche"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, segnalazione-crea, wizard, privacy, tailwind, lit, html-parity]
related:
  - "../concepts/theme-css-only-parity-rule.md"
  - "../concepts/design-comuni-site-wide-component-css-rule.md"
  - "../../../../../Modules/Fixcity/docs/wiki/comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md"
  - "../../../../../_bmad-output/implementation-artifacts/7-103-segnalazione-01-privacy-tailwind-lit-html-audit-correction-plan.md"
---

# Confronto: `segnalazione-01-privacy` (statico) vs `/it/tests/segnalazione-crea` (step 1)

## Fonti

| Fonte | Descrizione |
|--------|----------------|
| [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) | Template ufficiali comuni: Handlebars, SCSS, Webpack; build in `dist/` |
| [Pagina statica step 1](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html) | Reference HTML pubblicato |
| Locale | `http://127.0.0.1:8000/it/tests/segnalazione-crea` — CMS page `tests.segnalazione-crea` → block → `CreateTicketWizardWidget` |

---

## HTML di riferimento (Design Comuni — estratto annotato)

```html
<!-- skiplink -->
<div class="skiplink">
  <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
  <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>

<!-- Header 3-tier -->
<header class="it-header-wrapper">
  <!-- Slim bar: regione + lingua + accedi -->
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="it-header-slim-wrapper-content">
        <a class="navbar-brand">Nome della Regione</a>
        <!-- language switcher + login btn -->
        <a class="btn btn-primary btn-icon btn-full" href="...">Accedi all'area personale</a>
      </div>
    </div>
  </div>
  <!-- Center: logo + brand + social + cerca -->
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <div class="it-brand-wrapper">
        <a href="homepage.html">
          <svg><!-- logo-comune.svg --></svg>
          <div class="it-brand-text">
            <div class="it-brand-title">Il mio Comune</div>
            <div class="it-brand-tagline">Un comune da vivere</div>
          </div>
        </a>
      </div>
      <!-- socials + cerca -->
    </div>
    <!-- Navbar: Amministrazione | Novità | Servizi | Vivere il Comune -->
    <div class="it-header-navbar-wrapper" id="header-nav-wrapper">...</div>
  </div>
</header>

<!-- Breadcrumb -->
<nav aria-label="Percorso di navigazione" class="breadcrumb-container">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="homepage.html">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Servizi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Segnalazione disservizio</li>
  </ol>
</nav>

<main id="main-container">

  <!-- Titolo + stepper -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <h1 class="title-xxxlarge mb-4">Segnalazione disservizio</h1>
      </div>
      <div class="col-12">
        <div class="steppers">
          <div class="steppers-header">
            <ul>
              <li class="active">
                Autorizzazioni e condizioni
                <span class="visually-hidden">Attivo</span>
              </li>
              <li class="">Dati di segnalazione</li>
              <li class="">Riepilogo</li>
            </ul>
            <span class="steppers-index" aria-hidden="true">1/3</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contenuto step 1 (col-lg-8 = più stretto del titolo col-lg-10) -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8 pb-40 pb-lg-80">
        <!-- Privacy text -->
        <p class="text-paragraph mb-lg-4">
          Il Comune di Firenze gestisce i dati personali forniti e liberamente comunicati
          sulla base dell'articolo 13 del Regolamento (UE) 2016/679 ...
        </p>
        <p class="text-paragraph mb-0">
          Per i dettagli sul trattamento dei dati personali consulta l'
          <a href="#" class="t-primary">informativa sulla privacy.</a>
        </p>

        <!-- Checkbox -->
        <div class="form-check mt-4 mb-3 mt-md-40 mb-lg-40">
          <div class="checkbox-body d-flex align-items-center">
            <input type="checkbox" id="privacy" name="privacy-field" value="privacy-field">
            <label class="title-small-semi-bold pt-1" for="privacy">
              Ho letto e compreso l'informativa sulla privacy
            </label>
          </div>
        </div>

        <!-- CTA: singolo pulsante primario -->
        <button type="button" class="btn btn-primary mobile-full">
          <span class="">Avanti</span>
        </button>
      </div>
    </div>
  </div>

  <!-- "Contatta il comune" — sezione grigia dopo il form -->
  <div class="bg-grey-card shadow-contacts">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
          <div class="cmp-contacts">
            <div class="card w-100">
              <div class="card-body">
                <h2 class="title-medium-2-semi-bold">Contatta il comune</h2>
                <ul class="contact-list p-0">
                  <li><a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm"><!-- #it-help-circle --></svg>
                    <span>Leggi le domande frequenti</span>
                  </a></li>
                  <li><a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm"><!-- #it-mail --></svg>
                    <span>Richiedi assistenza</span>
                  </a></li>
                  <li><a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm"><!-- #it-hearing --></svg>
                    <span>Chiama il numero verde 05 0505</span>
                  </a></li>
                  <li><a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm"><!-- #it-calendar --></svg>
                    <span>Prenota appuntamento</span>
                  </a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>
```

---

## Delta visivi e strutturali — tabella completa

| # | Area | Reference (BI) | Locale (attuale) | Priorità | Owner |
|---|------|----------------|-----------------|----------|-------|
| H0 | **Header 3-tier structure** | `<header><div class="it-header-slim-wrapper">…<div class="it-header-center-wrapper">…<div class="it-header-navbar-wrapper">` | Identico ✅ | — | Tema |
| H1 | **Region name link** | `<a class="d-lg-block navbar-brand">Nome della Regione</a>` | `class="navbar-brand"` (colore bianco garantito da CSS `!important`, specificità vince su `a { color:#191919 }`) | ✅ Fixed P0 | Tema |
| H2 | **Slim wrapper content** | `it-header-slim-wrapper-content` con `justify-content: space-between` | Uguale ✅ | — | Tema |
| H3 | **Language dropdown** | `<button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">` + `.dropdown-menu` | Sixteen: `data-bs-toggle="dropdown"` + JS Alpine hook in `app.js` (Livewire `livewire:navigated`) | ✅ P2 | Tema |
| H4 | **Login CTA** | `<a class="btn btn-primary btn-icon btn-full">` con `<span class="rounded-icon">` + text | Uguale ✅ — classi `btn-primary btn-icon btn-full` | ✅ | Tema |
| H5 | **Center brand wrapper** | `it-brand-wrapper` con SVG logo + `it-brand-text` (title + tagline) | Uguale ✅ | — | Tema |
| H6 | **Social icons** | `<svg class="icon icon-sm icon-white align-top">` | Uguale ✅ — `icon-white` garantisce colore | — | Tema |
| H7 | **Search button** | `<button class="search-link rounded-icon">` + `<svg class="icon">` | Uguale ✅ | — | Tema |
| H8 | **Navbar toggler (mobile)** | `<button class="custom-navbar-toggler" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">` | Sixteen: Alpine `@click="toggle()"` senza `data-bs-*` (Livewire → Alpine conversion) | ✅ P2 | Tema |
| H9 | **Navbar nav items** | `<ul class="navbar-nav">` con 4 `<li class="nav-item"><a class="nav-link">Amministrazione</a>` etc. | Uguale ✅ — 4 item identici | — | Tema |
| H10 | **Secondary nav** | `<ul class="navbar-nav navbar-secondary">` con 4 link (Iscrizioni, Estate, Polizia, Argomenti) | Uguale ✅ | — | Tema |
| 1 | **Skiplink** | `visually-hidden-focusable` → `#main-container` | da verificare | P1 | Tema |
| 2 | **Breadcrumb** | `<nav aria-label="Percorso di navigazione"> <ol class="breadcrumb">` | **assente** / non visibile | **P1** | Tema |
| 3 | **Titolo h1** | `title-xxxlarge mb-4` in `col-lg-10` centrato | `title-xxxlarge` nel `.cmp-heading` | P2 | Tema |
| 4 | **Larghezza content** | `col-12 col-lg-8` (80% su large) per il corpo form | `col-12 col-lg-10 offset-lg-1` | P2 | Tema |
| 5 | **Stepper HTML** | `<ul>` con `<li class="active">` + `<span class="steppers-index">1/3</span>` | `@foreach` con classi BI — struttura simile | P2 | Tema |
| 6 | **Etichette step** | "Autorizzazioni e condizioni" · "Dati di segnalazione" · "Riepilogo" | dipende da traduzioni Filament | P2 | Modulo |
| 7 | **Testo privacy** | 2 `<p class="text-paragraph">` con link `class="t-primary"` | generato da Filament `Text` con HTML da traduzione | P2 | Modulo |
| 8 | **Checkbox wrapper** | `form-check > .checkbox-body.d-flex.align-items-center` | Filament `Checkbox` con wrapper BI | P2 | Tema/Modulo |
| 9 | **CTA "Avanti"** | `<button class="btn btn-primary mobile-full">` — un solo pulsante | pulsante nel template tema con `wire:click="nextStep"` | P1 | Tema |
| 10 | **"Contatta il comune"** | sezione `bg-grey-card shadow-contacts` con 4 link-list-items | **assente** nel wrapper attuale | **P1** | Tema/CMS |
| 11 | **Icone** | Sprite BI `bootstrap-italia/dist/svg/sprites.svg` | stesso path tema | P3 | Tema |
| 12 | **Stack CSS** | Bootstrap Italia (SCSS ufficiale) | BI emulato in Blade + tema Sixteen | P0 | Tema |
| 13 | **Inline `<style>`** | assente — tutto in SCSS compilato | `create-ticket.blade.php` contiene blocco `<style>` massivo | **CRITICO** | Modulo |

---

## Mappa classi Bootstrap Italia → Tailwind CSS

> Regola: **nessun blocco `<style>` in Blade** — tutto in `laravel/Themes/Sixteen/resources/css/`.

| Classe BI | Semantica | Tailwind equivalente |
|-----------|-----------|----------------------|
| `container` | max-width centrato | `container mx-auto px-4` |
| `row justify-content-center` | flex row centrata | `flex flex-wrap justify-center` |
| `col-12 col-lg-8` | full mobile, 8/12 desktop | `w-full lg:w-2/3` |
| `col-12 col-lg-10` | full mobile, 10/12 desktop | `w-full lg:w-5/6` |
| `title-xxxlarge` | h1 grande | `text-4xl lg:text-5xl font-bold` |
| `title-medium-2-semi-bold` | h2 medio semibold | `text-xl font-semibold` |
| `title-small-semi-bold` | label semibold | `text-sm font-semibold` |
| `text-paragraph` | body text | `text-base leading-relaxed` |
| `mb-lg-4` | margin bottom lg | `lg:mb-4` |
| `pb-40 pb-lg-80` | padding bottom custom | `pb-10 lg:pb-20` |
| `btn btn-primary` | pulsante primario | `btn-comuni-primary` (classe custom CSS tema) |
| `mobile-full` | full-width su mobile | `w-full sm:w-auto` |
| `visually-hidden` | solo screen reader | `sr-only` |
| `t-primary` | link colore primario | `text-primary underline` |
| `d-flex align-items-center` | flexbox centrato | `flex items-center` |
| `form-check` | wrapper checkbox | `flex gap-2 mt-4 mb-3` |
| `bg-grey-card` | sfondo grigio chiaro | `bg-gray-100` |
| `shadow-contacts` | ombra su contacts | `shadow-sm` |
| `p-contacts` | padding contacts | `py-8 px-4` |
| `icon icon-primary icon-sm` | svg icona piccola | `w-5 h-5 text-primary` |
| `contact-list` | lista contatti | `space-y-3 list-none p-0` |
| `list-item` | link in lista | `flex items-center gap-2 text-primary hover:underline` |
| `steppers-index` | "1/3" counter | classe custom `.steppers-index` in CSS tema |
| `visually-hidden-focusable` | skiplink | `sr-only focus:not-sr-only focus:absolute focus:p-2` |

---

## Problema critico: `create-ticket.blade.php` con `<style>` inline

File: `laravel/Modules/Fixcity/resources/views/filament/widgets/create-ticket.blade.php`

**Violazione**: contiene ~80 righe di CSS inline. **Da eliminare** o migrare.
**Azione**: spostare tutto in `laravel/Themes/Sixteen/resources/css/segnalazione-wizard.css` e referenziare via Vite.

---

## Sezione "Contatta il comune" — mancante nel locale

Questa sezione è presente nella pagina di riferimento ma **assente** nel wrapper locale.

**Scelte possibili:**
1. **Blocco CMS** nel JSON `tests.segnalazione-crea.json` — blocco separato dopo il widget wizard.
2. **Partial Blade tema** incluso nel layout della pagina test.
3. **Componente Lit** `<cmp-contatta-comune>` registrato nel tema.

**Raccomandazione (P1)**: opzione 1 — CMS block `contacts-card` già presente nel Fixcity module (`contact-card.blade.php`).

---

## Piano di correzione (fasi)

### P0 — Rimozione inline CSS (CRITICO)
- File: `laravel/Modules/Fixcity/resources/views/filament/widgets/create-ticket.blade.php`
- Azione: rimuovere il blocco `<style>` e spostare in `laravel/Themes/Sixteen/resources/css/`

### P1 — Struttura HTML parity
- Aggiungere skiplinks nel layout tema
- Ripristinare breadcrumb `<nav aria-label="Percorso di navigazione">`
- Aggiungere sezione "Contatta il comune" (CMS block o partial tema)
- Verificare che il CTA sia `<button class="w-full sm:w-auto">Avanti</button>` — un solo primario

### P2 — Migrazione classi BI → Tailwind
- Sostituire `row`/`col-*` con grid/flex Tailwind nel `ticket-create-wizard.blade.php`
- Larghezza corpo form: `col-12 col-lg-8` → `w-full lg:w-2/3`
- Checkbox wrapper: `form-check > .checkbox-body.d-flex` → `flex gap-2 mt-4`
- Mantenere `{{ $this->form }}` come unico punto di emissione Filament

### P3 — Icone
- Migrare da sprite BI `bootstrap-italia/dist/svg/sprites.svg` a SVG inline o sprite tema locale

### P4 — Componenti Lit (opzionale)
- `<design-comuni-stepper>` Lit Web Component se lo stepper ha logica complessa
- `<cmp-contatta-comune>` Lit per la card contatti con dati CMS

### Build finale
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

---

## Backlink

- Story BMAD: [`7-103`](../../../../../_bmad-output/implementation-artifacts/7-103-segnalazione-01-privacy-tailwind-lit-html-audit-correction-plan.md)
- Modulo Fixcity (stesso titolo, voce slim): [segnalazione-01-privacy-design-comuni-vs-local-wizard](../../../../Modules/Fixcity/docs/wiki/comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md)
- Log: [`../log.md`](../log.md)

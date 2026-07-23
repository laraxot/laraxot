# Homepage Design Comuni - Implementation Guide

> *"La homepage è il volto digitale del Comune. Deve essere perfetta."*

## 🎯 Panoramica

Replicare esattamente la homepage Design Comuni:
- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
- **Target**: http://fixcity.local/it/tests/homepage
- **Requisito**: HTML identico dentro `<body>` + CSS Bootstrap Italia

---

## 📊 Analisi Comparativa

### Design Comuni Structure

```
┌─────────────────────────────────────────────────────┐
│ HEADER (3 livelli)                                  │
│ 1. Slim: Regione | Lingua | Area Personale          │
│ 2. Center: Logo | Nome Comune | Social | Search     │
│ 3. Nav: Amministrazione | Novità | Servizi | Vivere │
├─────────────────────────────────────────────────────┤
│ MAIN CONTENT                                        │
│ ┌─────────────────────────────────────────────────┐ │
│ │ HERO: NOME DEL COMUNE                           │ │
│ │       CONTENUTI IN EVIDENZA                     │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ NEWS: Estate in città (card grande)             │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────┬─────────┬─────────┐                    │
│ │Sindaco  │Giunta   │Consiglio│                    │
│ └─────────┴─────────┴─────────┘                    │
│ ┌─────────────────────────────────────────────────┐ │
│ │ EVENTI: Settembre 2022 (lista)                  │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ ARGOMENTI IN EVIDENZA (5 card)                  │ │
│ │ Trasporto | Mobilità | Animale | Sport | Altri  │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ SITI TEMATICI (3 card)                          │ │
│ │ Mobilità | Turismo | Musei                       │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ SEARCH BAR: Cerca una parola chiave             │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ LINK UTILI: CIE | Residenza | Tributi | ...     │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ FEEDBACK: Stelle | Domande | Textarea           │ │
│ └─────────────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────┐ │
│ │ CONTATTI: Contatta | Assistenza | Disservizi    │ │
│ └─────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────┤
│ FOOTER (4 colonne + bottom bar)                     │
└─────────────────────────────────────────────────────┘
```

---

## 🎨 HTML Structure (Inside <body>)

### Complete Structure

```html
<body>
    <!-- Skip Links -->
    <a href="#main-content" class="visually-hidden">Skip to main content</a>
    
    <!-- Header -->
    <div class="it-header-wrapper">
        <!-- Level 1: Slim -->
        <div class="it-header-slim-wrapper">...</div>
        
        <!-- Level 2 & 3: Center + Nav -->
        <div class="it-nav-wrapper">
            <div class="it-header-center-wrapper">...</div>
            <div class="it-header-navbar-wrapper">...</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main id="main-content">
        
        <!-- Hero Section -->
        <section class="hero-section py-5">
            <div class="container">
                <h1 class="title-xxxlarge">NOME DEL COMUNE</h1>
                <p class="subtitle-small">CONTENUTI IN EVIDENZA</p>
            </div>
        </section>
        
        <!-- Featured News (Large Card) -->
        <section class="featured-news py-5 bg-light">
            <div class="container">
                <div class="card card-teaser shadow">
                    <div class="card-body">
                        <span class="date">18 mag 2022</span>
                        <h3 class="card-title">
                            <a href="#">PARTE L'ESTATE CON OLTRE 300 EVENTI...</a>
                        </h3>
                        <p class="card-text">Description...</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Government (3 Cards) -->
        <section class="government py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Organi di governo</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">Il Sindaco</h3>
                                <p class="card-text">Description...</p>
                                <a href="#" class="btn btn-primary btn-sm">Vai alla pagina</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">La Giunta Comunale</h3>
                                <p class="card-text">Description...</p>
                                <a href="#" class="btn btn-primary btn-sm">Vai alla pagina</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">Il Consiglio Comunale</h3>
                                <p class="card-text">Description...</p>
                                <a href="#" class="btn btn-primary btn-sm">Vai alla pagina</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Events (Calendar List) -->
        <section class="events py-5 bg-light">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Eventi</h2>
                <div class="card card-teaser-card">
                    <div class="card-body">
                        <div class="date-box">
                            <span class="month">SETTEMBRE</span>
                            <span class="day">2022</span>
                        </div>
                        <div class="event-content">
                            <h3 class="card-title h5">
                                <a href="#">Estate in città</a>
                            </h3>
                            <p class="card-text">Description...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Topics (5 Cards Grid) -->
        <section class="topics py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">ARGOMENTI IN EVIDENZA</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-topic">
                            <div class="card-body">
                                <h3 class="card-title h5">
                                    <a href="#">Trasporto pubblico</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-topic">
                            <div class="card-body">
                                <h3 class="card-title h5">
                                    <a href="#">Mobilità</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!-- More topics... -->
                </div>
            </div>
        </section>
        
        <!-- Thematic Sites (3 Cards) -->
        <section class="thematic-sites py-5 bg-light">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Siti tematici</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">Mobilità</h3>
                                <p class="card-text">Description...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">Turismo</h3>
                                <p class="card-text">Description...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-bg">
                            <div class="card-body">
                                <h3 class="card-title h5">Musei</h3>
                                <p class="card-text">Description...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Search Bar -->
        <section class="search py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Cerca nel sito</h2>
                <form class="search-form">
                    <div class="form-group">
                        <label for="search-input" class="visually-hidden">Cerca</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Cerca una parola chiave">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon"><use href="#it-search"></use></svg>
                        Invio
                    </button>
                </form>
            </div>
        </section>
        
        <!-- Useful Links -->
        <section class="useful-links py-5 bg-light">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Link utili</h2>
                <ul class="link-list">
                    <li><a href="#">Rilascio CIE</a></li>
                    <li><a href="#">Cambio di residenza</a></li>
                    <li><a href="#">Tributi online</a></li>
                    <li><a href="#">Prenotazione appuntamenti</a></li>
                    <li><a href="#">Rilascio tessera elettorale</a></li>
                </ul>
            </div>
        </section>
        
        <!-- Feedback -->
        <section class="feedback py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Valuta la pagina</h2>
                <form class="feedback-form">
                    <div class="star-rating">
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                        <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                    </div>
                    <div class="form-group mt-4">
                        <label>Quali aspetti di questo sito pensi di utilizzare più frequentemente?</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group mt-4">
                        <label>Quali difficoltà hai incontrato oggi nel completare le attività su questo sito?</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Invia</button>
                </form>
            </div>
        </section>
        
        <!-- Contact / Service -->
        <section class="contact-service py-5 bg-light">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-teaser">
                            <div class="card-body">
                                <h3 class="card-title h5">Contatta</h3>
                                <p class="card-text">Leggi le domande frequenti, richiedi assistenza, chiama il numero verde.</p>
                                <a href="#" class="btn btn-outline-primary">Contatta</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-teaser">
                            <div class="card-body">
                                <h3 class="card-title h5">Assistenza</h3>
                                <p class="card-text">Richiedi assistenza per problemi tecnici o informazioni.</p>
                                <a href="#" class="btn btn-outline-primary">Assistenza</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card card-teaser">
                            <div class="card-body">
                                <h3 class="card-title h5">Segnalazione disservizio</h3>
                                <p class="card-text">Segnala un disservizio o un problema sul territorio.</p>
                                <a href="#" class="btn btn-outline-primary">Segnala</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>
    
    <!-- Footer -->
    <footer class="it-footer">
        <x-section slug="footer" tpl="default" />
    </footer>
</body>
```

---

## 🎨 Bootstrap Italia Classes Reference

### Header Classes

```css
.it-header-wrapper          /* Main header wrapper */
.it-header-slim-wrapper     /* Top bar */
.it-header-center-wrapper   /* Center section */
.it-header-navbar-wrapper   /* Navigation */
.it-brand-wrapper           /* Logo + title */
.it-nav-scrollable          /* Scrollable nav */
```

### Layout Classes

```css
.container                  /* Max-width: 1200px */
.row                        /* Flexbox row */
.col-12                     /* Full width (mobile) */
.col-md-4                   /* 1/3 width (tablet+) */
.col-lg-3                   /* 1/4 width (desktop) */
.col-lg-4                   /* 1/3 width (desktop) */
.col-lg-6                   /* 1/2 width (desktop) */
.g-4                        /* Gap: 1.5rem */
```

### Section Classes

```css
.py-5                       /* Padding Y: 3rem */
.px-3                       /* Padding X: 1rem */
.mb-4                       /* Margin bottom: 1.5rem */
.mt-4                       /* Margin top: 1.5rem */
.bg-light                   /* Background: #f8f9fa */
.bg-dark                    /* Background: #17334f */
.text-white                 /* White text */
```

### Card Classes

```css
.card                       /* Base card */
.card-teaser                /* Teaser card */
.card-bg                    /* Background card */
.card-thumb                 /* Thumbnail card */
.card-topic                 /* Topic card */
.card-title                 /* Card title */
.card-text                  /* Card description */
.card-body                  /* Card body */
.shadow                     /* Shadow */
.shadow-sm                  /* Small shadow */
```

### Typography Classes

```css
.title-xxxlarge             /* H1 (5xl) */
.title-xxlarge              /* H2 (4xl) */
.title-xlarge               /* H3 (3xl) */
.title-large                /* H4 (2xl) */
.h1, .h2, .h3, .h4, .h5    /* Heading sizes */
.subtitle-small             /* Subtitle (sm) */
```

### Button Classes

```css
.btn                        /* Base button */
.btn-primary                /* Primary button */
.btn-outline-primary        /* Outline button */
.btn-sm                     /* Small button */
.btn-lg                     /* Large button */
```

### Form Classes

```css
.form-control               /* Input field */
.form-group                 /* Form group */
.star-rating                /* Star rating */
```

---

## 🔧 Implementation Steps

### Step 1: Create Blade View

**File**: `laravel/Themes/Sixteen/resources/views/design-comuni/pages/homepage.blade.php`

```blade
<x-layouts.bootstrap-italia>
    <x-accessibility.skiplinks />
    <x-bootstrap-italia.header />
    
    <main id="main-content">
        {{-- Hero Section --}}
        <section class="hero-section py-5">
            <div class="container">
                <h1 class="title-xxxlarge mb-2">NOME DEL COMUNE</h1>
                <p class="subtitle-small">CONTENUTI IN EVIDENZA</p>
            </div>
        </section>
        
        {{-- Featured News --}}
        <section class="featured-news py-5 bg-light">
            <div class="container">
                <div class="card card-teaser shadow">
                    <div class="card-body">
                        <span class="date text-muted small">18 mag 2022</span>
                        <h3 class="card-title h4 mt-2">
                            <a href="#" class="text-decoration-none">
                                PARTE L'ESTATE CON OLTRE 300 EVENTI...
                            </a>
                        </h3>
                        <p class="card-text mt-3">Description...</p>
                    </div>
                </div>
            </div>
        </section>
        
        {{-- Government --}}
        <section class="government py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">Organi di governo</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-bg shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title h5">Il Sindaco</h3>
                                <p class="card-text">Description...</p>
                                <a href="#" class="btn btn-primary btn-sm mt-3">Vai alla pagina</a>
                            </div>
                        </div>
                    </div>
                    {{-- More cards... --}}
                </div>
            </div>
        </section>
        
        {{-- Add all other sections... --}}
        
    </main>
    
    <x-section slug="footer" tpl="default" />
</x-layouts.bootstrap-italia>
```

### Step 2: Build & Deploy

```bash
cd laravel/Themes/Sixteen

# Build CSS/JS
npm run build

# Copy to public
npm run copy

# Clear cache
php artisan view:clear
php artisan route:clear

# Test
http://fixcity.local/it/tests/homepage
```

---

## ✅ Validation Checklist

### HTML Structure

- [ ] Skip links present
- [ ] Header with 3 levels
- [ ] Main content with `id="main-content"`
- [ ] All sections present (Hero, News, Government, Events, Topics, etc.)
- [ ] Footer with 4 columns
- [ ] Semantic HTML (section, article, nav)

### Bootstrap Italia Classes

- [ ] `.it-header-wrapper`
- [ ] `.container`, `.row`, `.col-*`
- [ ] `.card`, `.card-teaser`, `.card-bg`
- [ ] `.title-xxxlarge`, `.title-xxlarge`
- [ ] `.btn`, `.btn-primary`
- [ ] `.form-control`

### Accessibility

- [ ] ARIA labels on interactive elements
- [ ] `aria-hidden` on decorative icons
- [ ] Keyboard navigation works
- [ ] Focus visible on links
- [ ] Color contrast meets WCAG AA

### Responsive

- [ ] Mobile: Stack columns (col-12)
- [ ] Tablet: 2 columns (col-md-4)
- [ ] Desktop: 3-4 columns (col-lg-3, col-lg-4)

---

## 🧘 Developer Mantra

> *"La homepage è il volto digitale del Comune. Deve essere perfetta."*

> *"HTML identico a Design Comuni. CSS Bootstrap Italia. Perfezione."*

> *"Ogni classe Bootstrap Italia ha uno scopo. Usarla correttamente è dovere."*

---

## 🔗 References

### Internal
- [Footer Implementation](./FOOTER_IMPLEMENTATION.md)
- [Complete Implementation Guide](./COMPLETE_IMPLEMENTATION_GUIDE.md)
- [Bootstrap Italia Tailwind Conversion](./BOOTSTRAP_ITALIA_TAILWIND_CONVERSION.md)

### External
- [Design Comuni Homepage](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)
- [Bootstrap Italia Docs](https://italia.github.io/bootstrap-italia/)

---

**Version**: 1.0  
**Date**: 2026-03-30  
**Status**: ✅ Documentation Complete, Ready for Implementation  
**OpenViking URI**: `viking://themes/sixteen/docs/homepage-implementation`

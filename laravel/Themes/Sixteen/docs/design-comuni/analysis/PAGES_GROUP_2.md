# Design Comuni Pages HTML Structure Parity Analysis

## Group 2 Analysis Results

---

## Page: lista-risorse

### Status: Working

### Reference Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - cmp-hero / it-hero-wrapper (page title + description)
  - Section: Notizie in evidenza (3 featured news cards)
  - Section: Esplora tutte le novità (search input + 6 news cards + pagination)
  - Section: cmp-rating (feedback form with stars)
  - Section: cmp-contacts (contatti and problemi in citta)
- footer
- search-modal
- search-modal (duplicate)

### Local Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - cmp-heading (page title + lead)
  - Section: Notizie in evidenza (3 featured news cards using article.card-teaser)
  - Section: Esplora tutte le novità (cmp-input-search form + 6 news cards + cmp-pagination)
  - Section: cmp-rating (feedback form)
  - Section: cmp-contacts (contatta il comune)
- footer
- search-modal

### Structural Differences:
- Reference uses `cmp-hero` wrapper with `it-hero-wrapper`, local uses `cmp-heading`
- Reference has "Notizie in evidenza" section with white background, local uses "section section-muted" (grey)
- Reference has "Esplora tutte le novità" inside bg-grey-card container, local has separate sections
- Reference uses "cmp-input-search" with autocomplete wrapper, local uses simpler form structure
- Reference uses "pagination-wrapper" with icons, local uses "cmp-pagination"
- Reference has "bg-primary" wrapper around rating section, local uses "section section-primary"
- Reference has separate rating form sections (stars, positive/negative feedback), local uses simpler checkbox-based form
- Reference has "Problemi in città" section in contacts, local does not have this
- Reference has duplicate search-modal elements in body
- Card structure differs: reference uses card-wrapper + card, local uses article.card-teaser with h-100
- Local uses "card-text font-serif" for descriptions, reference uses "text-secondary"
- Local uses "badge bg-primary" for categories, reference uses category-top with links

### HTML Match: ~85%

---

## Page: lista-categorie

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - cmp-hero (page title + description)
  - Section: In evidenza (3 featured cards)
  - Section: Esplora per categoria (9 category cards)
  - Section: cmp-rating (feedback form)
  - Section: cmp-contacts (contatti)
- footer
- search-modal

### Local Sections Found:
- Error 500 - Server returned internal server error

### Structural Differences:
- Local page returns 500 error - unable to analyze structure

### HTML Match: 0%

---

## Page: lista-risorse-categorie

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - cmp-hero (page title + description)
  - Section: In evidenza (3 featured cards)
  - Section: Esplora tutte le {tipologia risorsa} (search + 6 news cards + pagination)
  - Section: Esplora per categoria (20 category cards)
  - Section: cmp-rating (feedback form)
  - Section: cmp-contacts (contatti)
- footer
- search-modal

### Local Sections Found:
- Error 500 - Server returned internal server error

### Structural Differences:
- Local page returns 500 error - unable to analyze structure

### HTML Match: 0%

---

## Page: mappa-sito

### Status: Working

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - Container with h1 "Mappa del sito"
  - link-list-wrapper with nested hierarchical structure:
    - Homepage (with 10 sub-items)
    - Amministrazione (with nested sub-items: Organi di governo, Aree amministrative, Uffici, Enti e fondazioni, Politici, Personale amministrativo, Documenti e dati)
    - Novità (with Notizie, Comunicati, Avvisi)
    - Servizi (with categorie and servizi)
    - Vivere il Comune (with Luoghi and Eventi)
    - Argomenti
- footer
- search-modal

### Local Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - cmp-hero with h1 "Mappa del sito"
  - Section: cmp-links-grid "Pagine principali" (6 card links)
  - Section: cmp-links-grid "Servizi" (3 card links)
  - Section: cmp-links-grid "Flussi" (3 card links)
- footer
- search-modal

### Structural Differences:
- Reference uses nested link-list structure, local uses grid-based card structure
- Reference shows full sitemap with all nested pages, local shows simplified 3-section card layout
- Local has breadcrumbs, reference does not
- Local has cmp-hero wrapper, reference has direct container
- Reference has much more detailed site structure with all sub-pages
- Local has simplified "Pagine principali", "Servizi", "Flussi" sections
- Reference includes argomenti section, local does not
- Reference includes homepage sub-links, local does not

### HTML Match: ~40%

---

## Page: contatti

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - cmp-hero (page title + description)
  - Section: cmp-contacts (comune and ufficio info with multiple office cards)
  - Section: cmp-rating (feedback)
- footer
- search-modal

### Local Sections Found:
- Error 500 - Server returned internal server error

### Structural Differences:
- Local page returns 500 error - unable to analyze structure

### HTML Match: 0%

---

## Summary

| Page | Status | HTML Match |
|------|--------|------------|
| lista-risorse | Working | ~85% |
| lista-categorie | Error (500) | 0% |
| lista-risorse-categorie | Error (500) | 0% |
| mappa-sito | Working | ~40% |
| contatti | Error (500) | 0% |

### Key Issues:
1. **500 Errors**: Three pages (lista-categorie, lista-risorse-categorie, contatti) return 500 errors on local environment
2. **mappa-sito**: Completely different structure - reference uses nested tree structure while local uses simplified grid cards
3. **lista-risorse**: Structural differences in hero, cards, and rating sections but content is similar
4. Missing components: "Problemi in citta" section in contacts, duplicate search-modal elements

# Design Comuni Pages HTML Structure Parity Analysis

## Group 1 Analysis Results

---

## Page: homepage

### Status: Working

### Reference Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - head-section (featured content)
  - calendario (calendar carousel with events)
  - evidence-section (argomenti in evidenza, altri argomenti, siti tematici)
  - useful-links-section (link utili with search)
- footer with rating section

### Local Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - head-section (featured content + search form)
  - calendario (calendar carousel with events)
  - evidence-section (argomenti in evidenza, altri argomenti, siti tematici)
  - useful-links-section (link utili with search)
- footer with rating section

### Structural Differences:
- Local has an additional cmp-search form inside head-section that is not in reference
- Evidence section background image path differs: reference uses `../assets/images/evidenza-header.png` vs local uses `/themes/Sixteen/design-comuni/assets/images/evidenza-header.png`
- Rating section at bottom is implemented differently (Alpine.js in local vs vanilla JS in reference)

### HTML Match: ~92%

---

## Page: domande-frequenti

### Status: Working

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - hero section (title + description)
  - cmp-input-search (search input)
  - cmp-accordion faq (17 accordion items)
- footer

### Local Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - hero section (title + description)
  - cmp-input-search (search input)
  - cmp-accordion faq (11 accordion items with Alpine.js)
- footer

### Structural Differences:
- Reference has 17 FAQ items, local has 11 FAQ items
- Local uses Alpine.js for accordion interactivity (x-data, x-show, @click)
- Reference uses Bootstrap's data-bs-toggle collapse
- Local accordion uses it-expand icon for chevron animation
- FAQ content differs: reference uses "Lorem Ipsum" placeholder, local uses actual answers

### HTML Match: ~75%

---

## Page: risultati-ricerca

### Status: Working

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - cmp-input-search-button (search form with button)
  - scroll-filter-wrapper (filters sidebar with Tipologie and Argomenti)
  - results area with cmp-card-latest-messages
  - modal-categories (mobile filter modal)
  - rating section (bg-primary)
- footer

### Local Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - cmp-input-search-button (search form with button)
  - scroll-filter-wrapper (filters - only Tipologie section)
  - results area with cmp-card-latest-messages
  - modal-categories (mobile filter modal)
  - rating section (bg-primary)
- footer

### Structural Differences:
- Reference has extensive Argomenti filter section with 50+ topics, local has simplified version with only 4 Tipologie
- Reference has more result cards (10+), local has 10 result cards
- Reference includes additional filters like "Luoghi" and "Eventi" counts
- Local has pre-populated search value "termine cercato"
- Rating section implementation differs (Alpine.js vs vanilla JS)

### HTML Match: ~70%

---

## Page: argomenti

### Status: Working

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section
  - "In evidenza" (3 card grid - Cultura, Sport, Famiglia)
  - "Esplora per argomento" (20+ card grid)
  - rating section (bg-primary)
  - contacts section (bg-grey-card shadow-contacts) with:
    - FAQ link
    - Assistenza link
    - Numero verde
    - Prenotazione
    - Segnala disservizio
- footer

### Local Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section
  - "In evidenza" (3 card grid - Cultura, Sport, Famiglia)
  - "Esplora per argomento" (20+ card grid)
  - rating section (different implementation with Alpine.js)
- footer (with additional links)

### Structural Differences:
- Reference has comprehensive contacts section with 5 contact options
- Local has simplified rating section using Alpine.js star rating
- Reference uses standard Bootstrap rating with form fields
- Local does NOT have the contacts section (bg-grey-card)
- Local topic cards have slightly different structure

### HTML Match: ~80%

---

## Page: argomento

### Status: Working

### Reference Sections Found:
- skiplink
- header
- main (it-hero-wrapper it-wrapped-container)
  - hero-card with breadcrumbs
  - novita section (news cards)
  - amministrazione section (people cards)
  - servizi section (service cards)
  - documenti section (document cards)
  - rating section (bg-primary)
  - contacts section (bg-grey-card)
- footer

### Local Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section
  - Servizi correlati section (3 service cards)
  - Documenti utili section (link list)
- search-modal (footer area)
- footer

### Structural Differences:
- Reference is a full topic detail page with multiple sections (novita, amministrazione, servizi, documenti)
- Local is a simplified version with only:
  - Servizi correlati (3 placeholder cards)
  - Documenti utili (2 link items)
- Reference has rich hero with image, breadcrumbs, department info cards
- Local has simple hero with just title
- Reference has complete contacts section
- Local does NOT have contacts section
- Reference has footer with extensive links
- Local has different footer structure

### HTML Match: ~45%

---

## Summary

| Page | Status | HTML Match % | Key Issues |
|------|--------|--------------|------------|
| homepage | Working | ~92% | Additional search form, rating implementation |
| domande-frequenti | Working | ~75% | FAQ count diff (17 vs 11), Alpine.js vs Bootstrap |
| risultati-ricerca | Working | ~70% | Missing Argomenti filters, simplified filters |
| argomenti | Working | ~80% | Missing contacts section, Alpine.js rating |
| argomento | Working | ~45% | Major: significantly simplified, missing sections |

## Recommendations

1. **homepage**: Minor - consider removing duplicate search or keeping for enhanced UX
2. **domande-frequenti**: Add remaining FAQ items, consider keeping Alpine.js for interactivity
3. **risultati-ricerca**: Add Argomenti filter section, restore full filter functionality
4. **argomenti**: Add contacts section, maintain Alpine.js rating (better UX)
5. **argomento**: Significant work needed - restore full section structure (novita, amministrazione, servizi, documenti), add contacts section

---
*Analysis Date: 2026-04-03*

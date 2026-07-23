# Design Comuni Pages HTML Structure Parity Analysis

## Group 3 Analysis Results

---

## Page: amministrazione

### Status: Working

### Reference Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - cmp-hero (hero with title "Amministrazione" and description)
  - "In evidenza" section (3 cards: Sindaco, Turismo, Pianificazione strategica)
  - "Esplora l'amministrazione" section (7 cards: Aree amministrative, Documenti e dati, Enti e fondazioni, Organi di governo, Personale amministrativo, Politici, Uffici)
  - cmp-rating (feedback section with star rating)
  - cmp-contacts (contatti: FAQ, assistenza, numero verde, prenotazione)
- footer
- search-modal

### Local Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - cmp-hero (hero with title "Amministrazione" and different description)
  - "Organi di governo" section (3 cards: Sindaco, Giunta comunale, Consiglio comunale)
  - "Documenti e dati" section (4 links: Statuto, Regolamenti, Bilanci, Bandi e avvisi)
- footer
- search-modal

### Structural Differences:
- Reference has "In evidenza" section with 3 topic cards (Sindaco, Turismo, Pianificazione strategica)
- Reference has "Esplora l'amministrazione" section with 7 category cards
- Local has simplified "Organi di governo" section (only 3 cards vs full structure)
- Local has "Documenti e dati" section (4 links) but missing many reference sections
- Reference has comprehensive cmp-rating feedback section with 5-star rating and multi-step form
- Reference has cmp-contacts section with contact options
- Local does NOT have rating section
- Local does NOT have contacts section
- Reference hero has full description about administration structure
- Local hero has shorter description

### HTML Match: ~55%

---

## Page: aree-amministrative

### Status: Error (404 - Page not found)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - hero section
  - aree list (grid of administrative areas)
  - cmp-rating
  - cmp-contacts
- footer

### Local Sections Found:
- N/A - Page returns 404

### Structural Differences:
- Page does not exist locally - returns HTTP 404
- This page should be implemented to match reference

### HTML Match: 0%

---

## Page: area-amministrativa-dettaglio

### Status: Error (404 - Page not found)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - hero section with area name
  - descrizione area
  - uffici list
  - responsabili
  - documenti
  - cmp-rating
  - cmp-contacts
- footer

### Local Sections Found:
- N/A - Page returns 404

### Structural Differences:
- Page does not exist locally - returns HTTP 404
- This is a detail page for individual administrative areas
- Needs implementation

### HTML Match: 0%

---

## Page: organo

### Status: Error (500 - Server Error)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - hero section
  - organo di indirizzo politico section (with people cards)
  - organo di revisione contabile section
  - altre informazioni section
  - cmp-rating
  - cmp-contacts
- footer

### Local Sections Found:
- N/A - Page returns 500 server error

### Structural Differences:
- Page exists but returns HTTP 500 (Internal Server Error)
- Needs debugging to determine cause of error

### HTML Match: 0%

---

## Page: persona

### Status: Error (500 - Server Error)

### Reference Sections Found:
- skiplink
- header (full header structure)
- main
  - breadcrumbs
  - persona section (foto, nome, ruolo, competenze)
  - contatti section
  - sedi section
  -cmp-rating
  - cmp-contacts
- footer

### Local Sections Found:
- N/A - Page returns 500 server error

### Structural Differences:
- Page exists but returns HTTP 500 (Internal Server Error)
- This is a detail page for political/administrative figures
- Needs debugging to determine cause of error

### HTML Match: 0%

---

## Summary

| Page | Status | HTML Match % | Key Issues |
|------|--------|--------------|------------|
| amministrazione | Working | ~55% | Missing "In evidenza" section, simplified "Esplora", missing rating/contacts sections |
| aree-amministrative | Error (404) | 0% | Page not implemented - needs creation |
| area-amministrativa-dettaglio | Error (404) | 0% | Page not implemented - needs creation |
| organo | Error (500) | 0% | Page exists but returns server error - needs debugging |
| persona | Error (500) | 0% | Page exists but returns server error - needs debugging |

## Recommendations

1. **amministrazione**: Add missing "In evidenza" section, restore "Esplora l'amministrazione" section with all 7 cards, add rating feedback section, add contacts section
2. **aree-amministrative**: Create new page with administrative areas grid list
3. **area-amministrativa-dettaglio**: Create new detail page for individual administrative areas
4. **organo**: Debug and fix 500 error - likely missing data or component issue
5. **persona**: Debug and fix 500 error - likely missing data or component issue

## Notes

- Pages are accessed via `/it/tests/[slug]` pattern using Folio routing with PageSlugMiddleware
- The CMS uses a block-based system where each page is composed of blocks defined in JSON data
- Missing pages likely need to have corresponding JSON data and block components created

---
*Analysis Date: 2026-04-03*

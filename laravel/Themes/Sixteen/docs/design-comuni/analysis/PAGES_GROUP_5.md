# Design Comuni Pages HTML Structure Parity Analysis

## Group 5 Analysis Results

---

## Page: eventi

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - hero section (#vivere-il-comune title)
  - hero image section (Colosseo)
  - Eventi in evidenza (6 event cards with calendar dates)
  - Luoghi in evidenza (6 place cards)
  - Rating section (bg-primary)
- footer with contacts section

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: evento-dettaglio

### Status: Error (Content Not Implemented)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs (Home > Vivere il Comune > Eventi > 363^ Festa di Sant'Efisio)
  - hero section with title and description
  - Share/Azioni dropdown buttons
  - Argomenti chips
  - Full-width image figure
  - INDICE DELLA PAGINA (table of contents)
  - Cos'è (event description)
  - A chi è rivolto
  - Luogo (with map)
  - Date e orari (timeline)
  - Costi (ticket prices)
  - Allegati (downloads)
  - Appuntamenti (related events)
  - Contatti
  - Patrocinato da
  - Sponsor
- footer

### Local Sections Found:
- skiplink
- header
- main
  - navigation breadcrumb items
  - hero section showing "Evento Dettaglio" placeholder title
  - Scenario di conversione alert box (sixteen::alerts.governance)
- footer

### Structural Differences:
- Reference shows "363^ Festa di Sant'Efisio" as title with full event description
- Local shows generic "Evento Dettaglio" placeholder
- Reference has complete event details: description, date, location, costs, contacts, sponsors
- Local has NO event details - just placeholder
- Reference has Argomenti chips section
- Local does NOT have Argomenti section
- Reference has INDICE DELLA PAGINA (table of contents)
- Local does NOT have table of contents
- Reference has Appuntamenti (related events)
- Local does NOT have related events
- Local shows governance conversion alert message

### HTML Match: ~20%

---

## Page: luoghi

### Status: Error (500)

### Reference Sections Found:
- Reference URL returned HTTP 404 - page not found in reference

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: luogo-dettaglio

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section
  - INDICE DELLA PAGINA
  - Cos'è
  - Luogo (with map)
  - Servizi offerti
  - Orari
  - Accessibilità
  - Contatti
- footer

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: novita

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section (#novita title)
  - In evidenza (3 featured news cards)
  - Esplora tutte le novità (search + 6 news list)
  - Esplora per categoria (3 category cards: Notizie, Comunicati, Avvisi)
  - Rating section (bg-primary)
- footer with contacts section

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Summary

| Page | Status | HTML Match % | Key Issues |
|------|--------|--------------|-------------|
| eventi | Error (500) | 0% | Server returning 500 error |
| evento-dettaglio | Error (Not Implemented) | ~20% | Placeholder content, missing event details, table of contents, related events |
| luoghi | Error (404/500) | 0% | Reference 404, Local 500 |
| luogo-dettaglio | Error (500) | 0% | Server returning 500 error |
| novita | Error (500) | 0% | Server returning 500 error |

## Recommendations

1. **eventi**: Fix 500 error - check server logs for cause
2. **evento-dettaglio**: Implement full page structure - add dynamic event title/description, table of contents, event details sections (date, location, costs, contacts), related events, Argomenti chips
3. **luoghi**: Verify reference page exists - if not, may need to skip or create from scratch
4. **luogo-dettaglio**: Fix 500 error - check server logs for cause
5. **novita**: Fix 500 error - check server logs for cause

---

*Analysis Date: 2026-04-03*

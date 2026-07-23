# Design Comuni Pages HTML Structure Parity Analysis

## Group 4 Analysis Results

---

## Page: documenti-dati

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header (it-header-wrapper with slim, nav, center, navbar sections)
- main
  - breadcrumbs
  - hero section (#documenti-e-dati title)
  - In evidenza (3 featured document cards)
  - Esplora i documenti (search + 3 document list)
  - Esplora per categoria (9 category cards: Accordi tra enti, Atti normativi, Dataset, Documenti Albo Pretorio, Documenti attività politica, Documenti funzionamento interno, Documenti tecnici di supporto, Documenti di programmazione e rendicontazione, Istanze, Modulistica)
  - Rating section (bg-primary)
- footer with contacts section

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: servizi

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero section (#servizi title)
  - Esplora tutti i servizi (search + 620 services list)
  - SERVIZI IN EVIDENZA (6 links)
  - Esplora per categoria (15 category cards)
  - Rating section
- footer

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: servizi-categoria

### Status: Error (Content Not Implemented)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs (Home > Servizi > Anagrafe e stato civile)
  - hero section (#anagrafe-e-stato-civile title + description)
  - Esplora tutti i servizi (search + 340 services list)
  - UFFICI (4 office links: Stato civile - Nascita e cittadinanza, Stato civile - DAT, Matrimoni e unioni civili, Separazioni e divorzi)
  - Bandi (1 bando card)
  - Rating section
- footer

### Local Sections Found:
- skiplink
- header
- main
  - navigation breadcrumb items (without actual page links)
  - hero section showing "Categoria di servizio" placeholder title
  - Scenario di conversione alert box (sixteen::alerts.governance)
- footer

### Structural Differences:
- Reference shows "Anagrafe e stato civile" as title with description
- Local shows generic "Categoria di servizio" placeholder
- Reference has full service list with 340 items
- Local has NO services list - just placeholder
- Reference has UFFICI section with 4 offices
- Local does NOT have UFFICI section
- Reference has Bandi section
- Local does NOT have Bandi section
- Local shows governance conversion alert message
- Local uses Alpine.js/Blade placeholders (translations not loaded)

### HTML Match: ~25%

---

## Page: servizio-dettaglio

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs (Home > Servizi > Educazione e formazione > Iscrizione alla scuola dell'infanzia)
  - hero section (#iscrizione-alla-scuola-dellinfanzia title + service status badge)
  - INDICE DELLA PAGINA (table of contents with 11 links)
  - A chi è rivolto (target audience)
  - Descrizione (description)
  - Come fare (how to)
  - Cosa serve (requirements)
  - Cosa si ottiene (result)
  - Tempi e scadenze (timelines)
  - Quanto costa (cost)
  - Accedi al servizio (service access buttons)
  - Ulteriori informazioni (additional info)
  - Condizioni di servizio (terms)
  - Contatti (contact info)
  - Argomenti (topics)
  - Rating section
- Contenuti correlati (related content)
- footer

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: evento

### Status: Error (Both 404/500)

### Reference Sections Found:
- Reference URL returned HTTP 404 - page not found

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Summary

| Page | Status | HTML Match % | Key Issues |
|------|--------|--------------|-------------|
| documenti-dati | Error (500) | 0% | Server returning 500 error |
| servizi | Error (500) | 0% | Server returning 500 error |
| servizio-dettaglio | Error (500) | 0% | Server returning 500 error |
| servizi-categoria | Error (Not Implemented) | ~25% | Placeholder content, missing services list, UFFICI, Bandi sections |
| evento | Error (404/500) | 0% | Reference 404, Local 500 |

## Recommendations

1. **documenti-dati**: Fix 500 error - check server logs for cause
2. **servizi**: Fix 500 error - check server logs for cause
3. **servizio-dettaglio**: Fix 500 error - check server logs for cause
4. **servizi-categoria**: Implement full page structure - add dynamic category title/description, service list, UFFICI section, Bandi section
5. **evento**: Reference page doesn't exist (404), need to verify if this page should be implemented

---
*Analysis Date: 2026-04-03*

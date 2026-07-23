# Design Comuni Pages HTML Structure Parity Analysis

## Group 6 Analysis Results

---

## Page: prenotazione-appuntamento

### Status: Error (500)

### Reference Sections Found:
- Reference URL returned HTTP 404 - page not found in reference

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: appuntamento-01-ufficio

### Status: Error (Not Implemented)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs (Home > Prenotazione appuntamento)
  - hero section (Prenotazione appuntamento title)
  - steppers header (1/5 - Luogo active)
  - Sidebar with INFORMAZIONI RICHIESTE accordion
  - cmp-card with Ufficio section and select dropdown (Affari generali, Anagrafe, etc.)
  - Navigation steps (Indietro, Avanti buttons)
  - Contact section (FAQ, Richiedi assistenza, Numero verde)
- footer

### Local Sections Found:
- skiplink
- header
- main
  - Hero section showing "Prenotazione Appuntamento" subtitle
  - Title "Appuntamento - Step 1 ufficio"
  - Description text about office selection
  - Scenario di conversione section (governance alert)
  - Source link to reference
- footer

### Structural Differences:
- Reference has full stepper UI with 5 steps (Luogo, Data e orario, Dettagli appuntamento, Richiedente, Riepilogo)
- Reference has breadcrumb navigation
- Reference has sidebar with INFORMAZIONI RICHIESTE accordion
- Reference has complete Ufficio form with select dropdown and office options
- Reference has navigation step buttons (Indietro, Avanti)
- Reference has contact section
- Local shows placeholder content with "Scenario di conversione" text
- Local shows governance alert message (sixteen::alerts.governance)
- Local does NOT have stepper UI
- Local does NOT have form elements
- Local does NOT have breadcrumb navigation

### HTML Match: ~15%

---

## Page: appuntamento-05-riepilogo

### Status: Error (Not Implemented)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - steppers header (5/5 - Riepilogo active, all previous confirmed)
  - cmp-card with cmp-info-summary sections:
    - Ufficio (tipologia ufficio, municipalità, modifica link)
    - Data e orario (data, ora, modifica link)
    - Dettagli appuntamento (motivo, dettagli, modifica link)
    - Richiedente (nome, cognome, email, modifica link)
  - Navigation buttons (Indietro, Salva Richiesta, Invia)
  - modal-terms for terms and conditions
  - Contact section
- footer

### Local Sections Found:
- skiplink
- header
- main
  - Hero section showing "Prenotazione Appuntamento" subtitle
  - Title "Appuntamento - Step 5 riepilogo"
  - Description text about summary review
  - Scenario di conversione section
  - Governance alert
  - Source link to reference
- footer

### Structural Differences:
- Reference has stepper with 5 completed steps showing all phases
- Reference has complete summary cards with all booking details
- Reference has Ufficio section with tipologia and municipalità
- Reference has Data e orario section with date and time
- Reference has Dettagli appuntamento section with motivo and dettagli
- Reference has Richiedente section with name, surname, email
- Reference has Salva Richiesta and Invia buttons
- Reference has modal for terms and conditions
- Local shows placeholder content only
- Local does NOT have any summary information
- Local does NOT have form navigation buttons

### HTML Match: ~10%

---

## Page: appuntamento-06-conferma

### Status: Error (500)

### Reference Sections Found:
- skiplink
- header
- main
  - breadcrumbs
  - hero with success icon and "Appuntamento confermato" title
  - Appointment details (Giovedì 11 marzo 2022, 10:00-10:30)
  - Email confirmation note
  - INDICE DELLA PAGINA (table of contents) with:
    - Cosa serve (documento di identità)
    - Indirizzo (office address)
    - Aggiungi al tuo calendario (Outlook, Google, iCloud)
  - cmp-rating section with star ratings and feedback form
  - Contact section
- footer

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Page: richiesta-assistenza

### Status: Error (500)

### Reference Sections Found:
- Reference URL returned HTTP 404 - page not found in reference

### Local Sections:
- Server returned HTTP 500 error

### HTML Match: 0%

---

## Summary

| Page | Status | HTML Match % | Key Issues |
|------|--------|--------------|-------------|
| prenotazione-appuntamento | Error (404/500) | 0% | Reference 404, Local 500 |
| appuntamento-01-ufficio | Error (Not Implemented) | ~15% | Placeholder content, missing stepper UI, form elements |
| appuntamento-05-riepilogo | Error (Not Implemented) | ~10% | Placeholder content, missing summary cards, navigation |
| appuntamento-06-conferma | Error (500) | 0% | Server returning 500 error |
| richiesta-assistenza | Error (404/500) | 0% | Reference 404, Local 500 |

## Recommendations

1. **prenotazione-appuntamento**: Verify if this is a landing/overview page - may need to check reference repo for correct path
2. **appuntamento-01-ufficio**: Implement full stepper UI with office selection form - this is the first step of the booking flow
3. **appuntamento-05-riepilogo**: Implement summary review page with all booking details and confirmation actions
4. **appuntamento-06-conferma**: Fix 500 error - check server logs for cause
5. **richiesta-assistenza**: Verify if reference page exists - if not, may need to create from scratch

---

*Analysis Date: 2026-04-03*

# Design Comuni Batch Structure Audit

Generato: 2026-04-03T10:11:26.955Z

| Pagina | JSON | Runtime | Body % | Main % | Overall % | Gate |
|---|---|---:|---:|---:|---:|---|
| homepage | yes | no | 90 | 15 | 38 | BLOCK |
| domande-frequenti | yes | no | 43 | 38 | 40 | BLOCK |
| risultati-ricerca | yes | no | 40 | 43 | 42 | BLOCK |
| argomenti | yes | no | 90 | 33 | 50 | BLOCK |
| argomento | yes | no | 90 | 0 | 27 | BLOCK |
| lista-risorse | yes | no | 42 | 0 | 13 | BLOCK |
| lista-categorie | yes | yes | 0 | 0 | 0 | BLOCK |
| lista-risorse-categorie | yes | yes | 0 | 0 | 0 | BLOCK |
| mappa-sito | yes | no | 90 | 0 | 27 | BLOCK |
| amministrazione | yes | no | 90 | 0 | 27 | BLOCK |
| aree-amministrative | no | no | 0 | 100 | 70 | BLOCK |
| area-amministrativa-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| organo | no | no | 0 | 100 | 70 | BLOCK |
| persona | no | no | 0 | 100 | 70 | BLOCK |
| persona-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| ufficio | no | no | 0 | 100 | 70 | BLOCK |
| ufficio-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| enti-e-fondazioni | no | no | 0 | 100 | 70 | BLOCK |
| ente-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| documenti-dati | yes | yes | 0 | 0 | 0 | BLOCK |
| documento-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| dataset-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| novita | yes | yes | 0 | 0 | 0 | BLOCK |
| novita-dettaglio | yes | no | 29 | 0 | 9 | BLOCK |
| servizi | yes | yes | 0 | 0 | 0 | BLOCK |
| servizi-categoria | yes | no | 38 | 0 | 11 | BLOCK |
| servizio-dettaglio | yes | yes | 0 | 0 | 0 | BLOCK |
| eventi | yes | yes | 0 | 0 | 0 | BLOCK |
| evento-dettaglio | yes | no | 17 | 0 | 5 | BLOCK |
| luoghi | no | no | 0 | 100 | 70 | BLOCK |
| luogo-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| contatti | no | no | 0 | 100 | 70 | BLOCK |
| pagamento | no | no | 0 | 100 | 70 | BLOCK |
| pagamento-dettaglio | no | no | 0 | 100 | 70 | BLOCK |
| prenotazione-appuntamento | no | no | 0 | 100 | 70 | BLOCK |
| appuntamento-01-ufficio | yes | no | 45 | 0 | 14 | BLOCK |
| appuntamento-01-ufficio-luogo | yes | no | 45 | 0 | 14 | BLOCK |
| appuntamento-02-data-orario | yes | no | 42 | 0 | 13 | BLOCK |
| appuntamento-03-dettagli | yes | no | 38 | 0 | 11 | BLOCK |
| appuntamento-04-richiedente | yes | no | 38 | 0 | 11 | BLOCK |
| appuntamento-04-richiedente-autenticato | yes | no | 38 | 0 | 11 | BLOCK |
| appuntamento-05-riepilogo | yes | no | 36 | 0 | 11 | BLOCK |
| appuntamento-06-conferma | yes | yes | 0 | 0 | 0 | BLOCK |
| richiesta-assistenza | no | no | 0 | 100 | 70 | BLOCK |
| assistenza-01-dati | yes | yes | 0 | 0 | 0 | BLOCK |
| assistenza-02-conferma | yes | no | 42 | 0 | 13 | BLOCK |
| segnalazione-disservizio | no | no | 0 | 100 | 70 | BLOCK |
| segnalazione-dettaglio | yes | no | 33 | 0 | 10 | BLOCK |
| ticket-list | yes | no | 38 | 0 | 11 | BLOCK |
| segnalazione-01-privacy | yes | no | 90 | 0 | 27 | BLOCK |
| segnalazione-02-dati | yes | no | 38 | 0 | 11 | BLOCK |
| segnalazione-03-riepilogo | yes | no | 38 | 0 | 11 | BLOCK |
| segnalazione-04-conferma | yes | no | 38 | 0 | 11 | BLOCK |
| segnalazione-area-personale | yes | no | 19 | 0 | 6 | BLOCK |

## Blocked Pages

### homepage

- Missing main children: `div.bg-primary`
- Extra local main children: `div.col-lg-6.order-1`, `div.it-rating-section.py-12`
- Screenshots: [reference](screenshots/batch-structure-audit/homepage/reference.png), [local](screenshots/batch-structure-audit/homepage/local.png)

### domande-frequenti

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.container`, `div#collapsefaq-20.accordion-collapse.collapse`, `div.col-12.col-lg-8`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `div.cmp-breadcrumbs`, `div.cmp-hero`, `div.cmp-input-search`, `div.cmp-accordion.faq`, `div#collapsefaq-3.accordion-collapse.collapse`, `div#collapsefaq-4.accordion-collapse.collapse`, `div#collapsefaq-5.accordion-collapse.collapse`, `div.bg-primary.faq-rating-section`, `div.it-footer-main.py-12`
- Screenshots: [reference](screenshots/batch-structure-audit/domande-frequenti/reference.png), [local](screenshots/batch-structure-audit/domande-frequenti/local.png)

### risultati-ricerca

- Missing main children: `h1#search-container.visually-hidden`, `div#main-container.container`, `div.it-example-modal`
- Extra local main children: `div.cmp-breadcrumbs`, `div#modal-categories.modal.fade`, `div.container.p-0`
- Screenshots: [reference](screenshots/batch-structure-audit/risultati-ricerca/reference.png), [local](screenshots/batch-structure-audit/risultati-ricerca/local.png)

### argomenti

- Missing main children: `div#main-container.container`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `div.cmp-breadcrumbs`, `div.cmp-hero`, `div.bg-primary.faq-rating-section`, `div.it-footer-main.py-12`
- Screenshots: [reference](screenshots/batch-structure-audit/argomenti/reference.png), [local](screenshots/batch-structure-audit/argomenti/local.png)

### argomento

- Missing main children: `div#main-container.it-hero-wrapper.it-wrapped-container`, `section#novita`, `section#amministrazione`, `section#servizi`, `section#documenti`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `div.cmp-breadcrumbs`, `div.cmp-hero`, `section.py-5`, `section.py-5.bg-it-gray-50`
- Screenshots: [reference](screenshots/batch-structure-audit/argomento/reference.png), [local](screenshots/batch-structure-audit/argomento/local.png)

### lista-risorse

- Missing main children: `div#main-container.container`, `div.container.py-5`, `div.bg-grey-card.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.section.section-muted`, `section.section`, `section.section.section-primary`, `section.useful-links-section`
- Screenshots: [reference](screenshots/batch-structure-audit/lista-risorse/reference.png), [local](screenshots/batch-structure-audit/lista-risorse/local.png)

### lista-categorie

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container.py-5`, `div#argomento.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/lista-categorie/reference.png), [local](screenshots/batch-structure-audit/lista-categorie/local.png)

### lista-risorse-categorie

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container.py-5`, `div.bg-grey-card.py-5`, `div#argomento.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/lista-risorse-categorie/reference.png), [local](screenshots/batch-structure-audit/lista-risorse-categorie/local.png)

### mappa-sito

- Missing main children: `div.container.my-4`
- Extra local main children: `div.cmp-breadcrumbs`, `div.cmp-hero`, `section.cmp-links-grid.mb-8`, `section.cmp-links-grid.mb-8`, `section.cmp-links-grid.mb-8`
- Screenshots: [reference](screenshots/batch-structure-audit/mappa-sito/reference.png), [local](screenshots/batch-structure-audit/mappa-sito/local.png)

### amministrazione

- Missing main children: `div#main-container.container`, `div.container`, `div.bg-grey-card.py-5`, `div.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `div.cmp-breadcrumbs`, `div.cmp-hero`, `section.py-5`, `section.py-5.bg-it-gray-50`
- Screenshots: [reference](screenshots/batch-structure-audit/amministrazione/reference.png), [local](screenshots/batch-structure-audit/amministrazione/local.png)

### aree-amministrative

- Missing JSON: `tests.aree-amministrative.json`
- Screenshots: [reference](screenshots/batch-structure-audit/aree-amministrative/reference.png), [local](screenshots/batch-structure-audit/aree-amministrative/local.png)

### area-amministrativa-dettaglio

- Missing JSON: `tests.area-amministrativa-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/area-amministrativa-dettaglio/reference.png), [local](screenshots/batch-structure-audit/area-amministrativa-dettaglio/local.png)

### organo

- Missing JSON: `tests.organo.json`
- Screenshots: [reference](screenshots/batch-structure-audit/organo/reference.png), [local](screenshots/batch-structure-audit/organo/local.png)

### persona

- Missing JSON: `tests.persona.json`
- Screenshots: [reference](screenshots/batch-structure-audit/persona/reference.png), [local](screenshots/batch-structure-audit/persona/local.png)

### persona-dettaglio

- Missing JSON: `tests.persona-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/persona-dettaglio/reference.png), [local](screenshots/batch-structure-audit/persona-dettaglio/local.png)

### ufficio

- Missing JSON: `tests.ufficio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/ufficio/reference.png), [local](screenshots/batch-structure-audit/ufficio/local.png)

### ufficio-dettaglio

- Missing JSON: `tests.ufficio-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/ufficio-dettaglio/reference.png), [local](screenshots/batch-structure-audit/ufficio-dettaglio/local.png)

### enti-e-fondazioni

- Missing JSON: `tests.enti-e-fondazioni.json`
- Screenshots: [reference](screenshots/batch-structure-audit/enti-e-fondazioni/reference.png), [local](screenshots/batch-structure-audit/enti-e-fondazioni/local.png)

### ente-dettaglio

- Missing JSON: `tests.ente-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/ente-dettaglio/reference.png), [local](screenshots/batch-structure-audit/ente-dettaglio/local.png)

### documenti-dati

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container.py-5`, `div.bg-grey-card.py-5`, `div.col-md-6.col-xl-4`, `p.text-center.text-paragraph-regular-medium`, `div#argomento.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/documenti-dati/reference.png), [local](screenshots/batch-structure-audit/documenti-dati/local.png)

### documento-dettaglio

- Missing JSON: `tests.documento-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/documento-dettaglio/reference.png), [local](screenshots/batch-structure-audit/documento-dettaglio/local.png)

### dataset-dettaglio

- Missing JSON: `tests.dataset-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/dataset-dettaglio/reference.png), [local](screenshots/batch-structure-audit/dataset-dettaglio/local.png)

### novita

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container.py-5`, `div.bg-grey-card.py-5`, `div#argomento.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/novita/reference.png), [local](screenshots/batch-structure-audit/novita/local.png)

### novita-dettaglio

- Missing main children: `div#main-container.container`, `div.dropdown.d-inline`, `div.dropdown-menu.shadow-lg`, `div.mt-4.mb-4`, `div.container-fluid.my-3`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `nav.py-3.border-b`, `section.hero-block.hero-white`, `div.details-block.details-vertical`, `div.list-block.list-icon`
- Screenshots: [reference](screenshots/batch-structure-audit/novita-dettaglio/reference.png), [local](screenshots/batch-structure-audit/novita-dettaglio/local.png)

### servizi

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.bg-grey-card`, `div.container.py-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/servizi/reference.png), [local](screenshots/batch-structure-audit/servizi/local.png)

### servizi-categoria

- Missing main children: `div#main-container.container`, `div.container`, `div.bg-grey-card`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/servizi-categoria/reference.png), [local](screenshots/batch-structure-audit/servizi-categoria/local.png)

### servizio-dettaglio

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`, `span.hr-shadow-sm.d-none`, `div.col-12.col-lg-6`, `h2.title-medium-2-semi-bold.mt-4`, `ul.contact-list.p-0`
- Screenshots: [reference](screenshots/batch-structure-audit/servizio-dettaglio/reference.png), [local](screenshots/batch-structure-audit/servizio-dettaglio/local.png)

### eventi

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `section.hero-img.mb-20`, `div.bg-grey-dsk.py-5`, `div.col-lg-6.col-xl-4`, `div.col-lg-6.col-xl-4`, `div.col-lg-6.col-xl-4`, `div.d-flex.justify-content-end`, `div.container.p-3`, `div.col-lg-6.col-xl-4`, `div.col-lg-6.col-xl-4`, `div.col-lg-6.col-xl-4`, `div.col-lg-6.col-xl-4`, `div.d-flex.justify-content-end`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/eventi/reference.png), [local](screenshots/batch-structure-audit/eventi/local.png)

### evento-dettaglio

- Missing main children: `div#main-container.container`, `div.dropdown.d-inline`, `div.dropdown-menu.shadow-lg`, `div.mt-4.mb-4`, `div.container-fluid.my-3`, `div.container`, `article#date-e-orari.it-page-section.mb-5`, `article#costi.it-page-section.mb-5`, `article#allegati.it-page-section.mb-5`, `article#appuntamenti.it-page-section.mb-5`, `div.card-wrapper.card-teaser`, `article#contatti.it-page-section.mb-5`, `h4.h5`, `div.card.card-teaser`, `div.card-body`, `article#patrocinio.it-page-section.mb-5`, `article#sponsor.it-page-section.mb-5`, `article#ultimo-aggiornamento.it-page-section.mt-5`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `nav.py-3.border-b`, `section.hero-block.hero-white`, `div.details-block.details-vertical`, `div.list-block.list-icon`
- Screenshots: [reference](screenshots/batch-structure-audit/evento-dettaglio/reference.png), [local](screenshots/batch-structure-audit/evento-dettaglio/local.png)

### luoghi

- Missing JSON: `tests.luoghi.json`
- Screenshots: [reference](screenshots/batch-structure-audit/luoghi/reference.png), [local](screenshots/batch-structure-audit/luoghi/local.png)

### luogo-dettaglio

- Missing JSON: `tests.luogo-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/luogo-dettaglio/reference.png), [local](screenshots/batch-structure-audit/luogo-dettaglio/local.png)

### contatti

- Missing JSON: `tests.contatti.json`
- Screenshots: [reference](screenshots/batch-structure-audit/contatti/reference.png), [local](screenshots/batch-structure-audit/contatti/local.png)

### pagamento

- Missing JSON: `tests.pagamento.json`
- Screenshots: [reference](screenshots/batch-structure-audit/pagamento/reference.png), [local](screenshots/batch-structure-audit/pagamento/local.png)

### pagamento-dettaglio

- Missing JSON: `tests.pagamento-dettaglio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/pagamento-dettaglio/reference.png), [local](screenshots/batch-structure-audit/pagamento-dettaglio/local.png)

### prenotazione-appuntamento

- Missing JSON: `tests.prenotazione-appuntamento.json`
- Screenshots: [reference](screenshots/batch-structure-audit/prenotazione-appuntamento/reference.png), [local](screenshots/batch-structure-audit/prenotazione-appuntamento/local.png)

### appuntamento-01-ufficio

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-01-ufficio/reference.png), [local](screenshots/batch-structure-audit/appuntamento-01-ufficio/local.png)

### appuntamento-01-ufficio-luogo

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-01-ufficio-luogo/reference.png), [local](screenshots/batch-structure-audit/appuntamento-01-ufficio-luogo/local.png)

### appuntamento-02-data-orario

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `p.title-xsmall.d-lg-none`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-02-data-orario/reference.png), [local](screenshots/batch-structure-audit/appuntamento-02-data-orario/local.png)

### appuntamento-03-dettagli

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `p.title-xsmall.d-lg-none`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-03-dettagli/reference.png), [local](screenshots/batch-structure-audit/appuntamento-03-dettagli/local.png)

### appuntamento-04-richiedente

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `span.steppers-index`, `p.title-xsmall.d-lg-none`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-04-richiedente/reference.png), [local](screenshots/batch-structure-audit/appuntamento-04-richiedente/local.png)

### appuntamento-04-richiedente-autenticato

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `span.steppers-index`, `p.title-xsmall.d-lg-none`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-04-richiedente-autenticato/reference.png), [local](screenshots/batch-structure-audit/appuntamento-04-richiedente-autenticato/local.png)

### appuntamento-05-riepilogo

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `li.active`, `span.steppers-index`, `div.container`, `div.bg-grey-card.shadow-contacts`, `div.cmp-modal`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-05-riepilogo/reference.png), [local](screenshots/batch-structure-audit/appuntamento-05-riepilogo/local.png)

### appuntamento-06-conferma

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/appuntamento-06-conferma/reference.png), [local](screenshots/batch-structure-audit/appuntamento-06-conferma/local.png)

### richiesta-assistenza

- Missing JSON: `tests.richiesta-assistenza.json`
- Screenshots: [reference](screenshots/batch-structure-audit/richiesta-assistenza/reference.png), [local](screenshots/batch-structure-audit/richiesta-assistenza/local.png)

### assistenza-01-dati

- Local runtime error detected
- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Screenshots: [reference](screenshots/batch-structure-audit/assistenza-01-dati/reference.png), [local](screenshots/batch-structure-audit/assistenza-01-dati/local.png)

### assistenza-02-conferma

- Missing main children: `div#main-container.container`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/assistenza-02-conferma/reference.png), [local](screenshots/batch-structure-audit/assistenza-02-conferma/local.png)

### segnalazione-disservizio

- Missing JSON: `tests.segnalazione-disservizio.json`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-disservizio/reference.png), [local](screenshots/batch-structure-audit/segnalazione-disservizio/local.png)

### segnalazione-dettaglio

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`, `span.hr-shadow-sm.d-none`, `div.col-12.col-lg-6`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-dettaglio/reference.png), [local](screenshots/batch-structure-audit/segnalazione-dettaglio/local.png)

### ticket-list

- Missing main children: `div#main-container.container`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`, `div.it-example-modal`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/ticket-list/reference.png), [local](screenshots/batch-structure-audit/ticket-list/local.png)

### segnalazione-01-privacy

- Missing main children: `div#main-container.container`, `div.container`, `div.container`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-01-privacy/reference.png), [local](screenshots/batch-structure-audit/segnalazione-01-privacy/local.png)

### segnalazione-02-dati

- Missing main children: `div#main-container.container`, `div.row`, `div.col-12.col-lg-8`, `section#report-author.it-page-section`, `div.cmp-nav-steps`, `div#alert-message.alert.alert-success`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-02-dati/reference.png), [local](screenshots/batch-structure-audit/segnalazione-02-dati/local.png)

### segnalazione-03-riepilogo

- Missing main children: `div#main-container.container`, `div.row.justify-content-center`, `div.bg-grey-card.shadow-contacts`, `div.cmp-modal`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-03-riepilogo/reference.png), [local](screenshots/batch-structure-audit/segnalazione-03-riepilogo/local.png)

### segnalazione-04-conferma

- Missing main children: `div#main-container.container`, `div.row.justify-content-center`, `div.bg-primary`, `div.bg-grey-card.shadow-contacts`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-04-conferma/reference.png), [local](screenshots/batch-structure-audit/segnalazione-04-conferma/local.png)

### segnalazione-area-personale

- Missing main children: `div#main-container.container`, `div.it-page-sections-container`, `div#data-ex-tab2.tab-pane`, `div#data-ex-tab3.tab-pane`, `div.cmp-accordion`, `div.accordion-item`, `div.accordion-item`, `button.btn.accordion-view-more`, `section#payments.it-page-section.mb-50`, `div.cmp-input-search`, `div.cmp-accordion`, `div.accordion-item`, `div.accordion-item`, `button.btn.accordion-view-more`, `div#data-ex-tab4.tab-pane`, `div.bg-grey-card.shadow-contacts`, `div.cmp-modal`
- Extra local main children: `section.py-12.bg-white`, `section.py-8.bg-white`, `div.bg-blue-50.border-l-4`, `div.mt-4`
- Screenshots: [reference](screenshots/batch-structure-audit/segnalazione-area-personale/reference.png), [local](screenshots/batch-structure-audit/segnalazione-area-personale/local.png)

## Candidate Pages For CSS/JS


## JSON Source Reminder

- Blade route: [laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php)
- JSON content lives in `laravel/config/local/fixcity/database/content/pages/tests.<pagina>.json`
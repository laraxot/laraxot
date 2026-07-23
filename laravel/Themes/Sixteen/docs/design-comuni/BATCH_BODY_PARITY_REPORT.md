# Batch Body Parity Report

Date: 2026-04-03

This report compares the HTML structure inside `<body>` excluding scripts for the Design Comuni reference pages and the local `/it/tests/<pagina>` routes.

Method:
- fetch both pages preserving HTTP status
- remove all `<script>` nodes
- extract the `<body>` fragment
- compute a structural similarity score from the ordered opening-tag sequence
- save per-page artifacts in `docs/design-comuni/batch-body-parity/<pagina>/`

Status meanings:
- `READY`: match >= 90 and both pages return HTTP 200
- `PARTIAL`: match between 70 and 89.9 and both pages return HTTP 200
- `FAIL`: HTTP error or structure still far from parity

| Page | Ref | Local | Ref tags | Local tags | Match % | Status | Notes | Artifacts |
| --- | --- | --- | ---: | ---: | ---: | --- | --- | --- |
| assistenza-01-dati | 200 | 500 | 524 | 1699 | 11.9 | FAIL | local=500 | [ref](batch-body-parity/assistenza-01-dati/reference-body.html) / [local](batch-body-parity/assistenza-01-dati/local-body.html) |
| appuntamento-06-conferma | 200 | 500 | 605 | 1699 | 14.1 | FAIL | local=500 | [ref](batch-body-parity/appuntamento-06-conferma/reference-body.html) / [local](batch-body-parity/appuntamento-06-conferma/local-body.html) |
| lista-categorie | 200 | 500 | 638 | 1699 | 15.1 | FAIL | local=500 | [ref](batch-body-parity/lista-categorie/reference-body.html) / [local](batch-body-parity/lista-categorie/local-body.html) |
| documenti-dati | 200 | 500 | 699 | 1699 | 16.6 | FAIL | local=500 | [ref](batch-body-parity/documenti-dati/reference-body.html) / [local](batch-body-parity/documenti-dati/local-body.html) |
| novita | 200 | 500 | 710 | 1699 | 17.1 | FAIL | local=500 | [ref](batch-body-parity/novita/reference-body.html) / [local](batch-body-parity/novita/local-body.html) |
| servizi | 200 | 500 | 727 | 1699 | 17.7 | FAIL | local=500 | [ref](batch-body-parity/servizi/reference-body.html) / [local](batch-body-parity/servizi/local-body.html) |
| eventi | 200 | 500 | 789 | 1699 | 18.6 | FAIL | local=500 | [ref](batch-body-parity/eventi/reference-body.html) / [local](batch-body-parity/eventi/local-body.html) |
| segnalazione-area-personale | 200 | 200 | 886 | 384 | 19.2 | FAIL | tag-count 886/384; main structure drift | [ref](batch-body-parity/segnalazione-area-personale/reference-body.html) / [local](batch-body-parity/segnalazione-area-personale/local-body.html) |
| servizio-dettaglio | 200 | 500 | 877 | 1805 | 19.4 | FAIL | local=500 | [ref](batch-body-parity/servizio-dettaglio/reference-body.html) / [local](batch-body-parity/servizio-dettaglio/local-body.html) |
| lista-risorse-categorie | 200 | 500 | 830 | 1699 | 20.5 | FAIL | local=500 | [ref](batch-body-parity/lista-risorse-categorie/reference-body.html) / [local](batch-body-parity/lista-risorse-categorie/local-body.html) |
| aree-amministrative | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/aree-amministrative/reference-body.html) / [local](batch-body-parity/aree-amministrative/local-body.html) |
| area-amministrativa-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/area-amministrativa-dettaglio/reference-body.html) / [local](batch-body-parity/area-amministrativa-dettaglio/local-body.html) |
| organo | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/organo/reference-body.html) / [local](batch-body-parity/organo/local-body.html) |
| persona | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/persona/reference-body.html) / [local](batch-body-parity/persona/local-body.html) |
| persona-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/persona-dettaglio/reference-body.html) / [local](batch-body-parity/persona-dettaglio/local-body.html) |
| ufficio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/ufficio/reference-body.html) / [local](batch-body-parity/ufficio/local-body.html) |
| ufficio-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/ufficio-dettaglio/reference-body.html) / [local](batch-body-parity/ufficio-dettaglio/local-body.html) |
| enti-e-fondazioni | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/enti-e-fondazioni/reference-body.html) / [local](batch-body-parity/enti-e-fondazioni/local-body.html) |
| ente-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/ente-dettaglio/reference-body.html) / [local](batch-body-parity/ente-dettaglio/local-body.html) |
| documento-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/documento-dettaglio/reference-body.html) / [local](batch-body-parity/documento-dettaglio/local-body.html) |
| dataset-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/dataset-dettaglio/reference-body.html) / [local](batch-body-parity/dataset-dettaglio/local-body.html) |
| luoghi | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/luoghi/reference-body.html) / [local](batch-body-parity/luoghi/local-body.html) |
| luogo-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/luogo-dettaglio/reference-body.html) / [local](batch-body-parity/luogo-dettaglio/local-body.html) |
| contatti | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/contatti/reference-body.html) / [local](batch-body-parity/contatti/local-body.html) |
| pagamento | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/pagamento/reference-body.html) / [local](batch-body-parity/pagamento/local-body.html) |
| pagamento-dettaglio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/pagamento-dettaglio/reference-body.html) / [local](batch-body-parity/pagamento-dettaglio/local-body.html) |
| prenotazione-appuntamento | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/prenotazione-appuntamento/reference-body.html) / [local](batch-body-parity/prenotazione-appuntamento/local-body.html) |
| richiesta-assistenza | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/richiesta-assistenza/reference-body.html) / [local](batch-body-parity/richiesta-assistenza/local-body.html) |
| segnalazione-disservizio | 404 | 500 | 19 | 24 | 23.8 | FAIL | reference=404; local=500 | [ref](batch-body-parity/segnalazione-disservizio/reference-body.html) / [local](batch-body-parity/segnalazione-disservizio/local-body.html) |
| evento-dettaglio | 200 | 200 | 905 | 415 | 28.8 | FAIL | tag-count 905/415; main structure drift | [ref](batch-body-parity/evento-dettaglio/reference-body.html) / [local](batch-body-parity/evento-dettaglio/local-body.html) |
| segnalazione-dettaglio | 200 | 200 | 804 | 384 | 31.1 | FAIL | tag-count 804/384; main structure drift | [ref](batch-body-parity/segnalazione-dettaglio/reference-body.html) / [local](batch-body-parity/segnalazione-dettaglio/local-body.html) |
| ticket-list | 200 | 200 | 775 | 384 | 32.4 | FAIL | tag-count 775/384; main structure drift | [ref](batch-body-parity/ticket-list/reference-body.html) / [local](batch-body-parity/ticket-list/local-body.html) |
| servizi-categoria | 200 | 200 | 663 | 384 | 38.6 | FAIL | tag-count 663/384; main structure drift | [ref](batch-body-parity/servizi-categoria/reference-body.html) / [local](batch-body-parity/servizi-categoria/local-body.html) |
| novita-dettaglio | 200 | 200 | 665 | 415 | 38.8 | FAIL | tag-count 665/415; main structure drift | [ref](batch-body-parity/novita-dettaglio/reference-body.html) / [local](batch-body-parity/novita-dettaglio/local-body.html) |
| argomento | 200 | 200 | 715 | 421 | 39.1 | FAIL | tag-count 715/421; main structure drift | [ref](batch-body-parity/argomento/reference-body.html) / [local](batch-body-parity/argomento/local-body.html) |
| risultati-ricerca | 200 | 200 | 1024 | 674 | 39.9 | FAIL | tag-count 1024/674; main structure drift | [ref](batch-body-parity/risultati-ricerca/reference-body.html) / [local](batch-body-parity/risultati-ricerca/local-body.html) |
| segnalazione-04-conferma | 200 | 200 | 551 | 384 | 44.2 | FAIL | tag-count 551/384; main structure drift | [ref](batch-body-parity/segnalazione-04-conferma/reference-body.html) / [local](batch-body-parity/segnalazione-04-conferma/local-body.html) |
| segnalazione-02-dati | 200 | 200 | 559 | 384 | 46.0 | FAIL | tag-count 559/384; main structure drift | [ref](batch-body-parity/segnalazione-02-dati/reference-body.html) / [local](batch-body-parity/segnalazione-02-dati/local-body.html) |
| mappa-sito | 200 | 200 | 615 | 476 | 46.3 | FAIL | tag-count 615/476; main structure drift | [ref](batch-body-parity/mappa-sito/reference-body.html) / [local](batch-body-parity/mappa-sito/local-body.html) |
| assistenza-02-conferma | 200 | 200 | 526 | 384 | 46.4 | FAIL | tag-count 526/384; main structure drift | [ref](batch-body-parity/assistenza-02-conferma/reference-body.html) / [local](batch-body-parity/assistenza-02-conferma/local-body.html) |
| appuntamento-01-ufficio-luogo | 200 | 200 | 526 | 384 | 46.5 | FAIL | tag-count 526/384; main structure drift | [ref](batch-body-parity/appuntamento-01-ufficio-luogo/reference-body.html) / [local](batch-body-parity/appuntamento-01-ufficio-luogo/local-body.html) |
| amministrazione | 200 | 200 | 601 | 426 | 47.2 | FAIL | tag-count 601/426; main structure drift | [ref](batch-body-parity/amministrazione/reference-body.html) / [local](batch-body-parity/amministrazione/local-body.html) |
| appuntamento-05-riepilogo | 200 | 200 | 533 | 384 | 47.4 | FAIL | tag-count 533/384; main structure drift | [ref](batch-body-parity/appuntamento-05-riepilogo/reference-body.html) / [local](batch-body-parity/appuntamento-05-riepilogo/local-body.html) |
| appuntamento-02-data-orario | 200 | 200 | 518 | 384 | 48.1 | FAIL | tag-count 518/384; main structure drift | [ref](batch-body-parity/appuntamento-02-data-orario/reference-body.html) / [local](batch-body-parity/appuntamento-02-data-orario/local-body.html) |
| segnalazione-03-riepilogo | 200 | 200 | 523 | 384 | 48.7 | FAIL | tag-count 523/384; main structure drift | [ref](batch-body-parity/segnalazione-03-riepilogo/reference-body.html) / [local](batch-body-parity/segnalazione-03-riepilogo/local-body.html) |
| homepage | 200 | 200 | 836 | 751 | 49.5 | FAIL | tag-count 836/751; main structure drift | [ref](batch-body-parity/homepage/reference-body.html) / [local](batch-body-parity/homepage/local-body.html) |
| appuntamento-04-richiedente-autenticato | 200 | 200 | 505 | 384 | 49.7 | FAIL | tag-count 505/384; main structure drift | [ref](batch-body-parity/appuntamento-04-richiedente-autenticato/reference-body.html) / [local](batch-body-parity/appuntamento-04-richiedente-autenticato/local-body.html) |
| appuntamento-04-richiedente | 200 | 200 | 489 | 384 | 50.6 | FAIL | tag-count 489/384; main structure drift | [ref](batch-body-parity/appuntamento-04-richiedente/reference-body.html) / [local](batch-body-parity/appuntamento-04-richiedente/local-body.html) |
| appuntamento-03-dettagli | 200 | 200 | 490 | 384 | 51.4 | FAIL | tag-count 490/384; main structure drift | [ref](batch-body-parity/appuntamento-03-dettagli/reference-body.html) / [local](batch-body-parity/appuntamento-03-dettagli/local-body.html) |
| lista-risorse | 200 | 200 | 687 | 599 | 51.8 | FAIL | tag-count 687/599; main structure drift | [ref](batch-body-parity/lista-risorse/reference-body.html) / [local](batch-body-parity/lista-risorse/local-body.html) |
| appuntamento-01-ufficio | 200 | 200 | 482 | 384 | 51.8 | FAIL | tag-count 482/384; main structure drift | [ref](batch-body-parity/appuntamento-01-ufficio/reference-body.html) / [local](batch-body-parity/appuntamento-01-ufficio/local-body.html) |
| domande-frequenti | 200 | 200 | 767 | 669 | 53.5 | FAIL | tag-count 767/669; main structure drift | [ref](batch-body-parity/domande-frequenti/reference-body.html) / [local](batch-body-parity/domande-frequenti/local-body.html) |
| argomenti | 200 | 200 | 704 | 602 | 54.5 | FAIL | tag-count 704/602; main structure drift | [ref](batch-body-parity/argomenti/reference-body.html) / [local](batch-body-parity/argomenti/local-body.html) |
| segnalazione-01-privacy | 200 | 200 | 430 | 384 | 57.9 | FAIL | tag-count 430/384; main structure drift | [ref](batch-body-parity/segnalazione-01-privacy/reference-body.html) / [local](batch-body-parity/segnalazione-01-privacy/local-body.html) |

Summary:
- READY: 0
- PARTIAL: 0
- FAIL: 54

Next step:
- Start CSS/JS parity only on `READY` pages first.
- For `PARTIAL` and `FAIL`, fix Blade/JSON structure before visual polish.

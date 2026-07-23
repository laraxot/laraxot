# Batch Body Parity Audit

Date: 2026-04-03

Script: [../../../bashscripts/design-comuni/batch-body-parity.sh](../../../bashscripts/design-comuni/batch-body-parity.sh)

Questo report confronta il `body` senza `script` tra reference Design Comuni e pagina locale `/it/tests/<slug>` per tutte le pagine richieste.

| Pagina | Ref HTTP | Local HTTP | Structural Parity | Esito |
|---|---:|---:|---:|---|
| `homepage` | 200 | 200 | 68.6% | HTML_DELTA |
| `domande-frequenti` | 200 | 200 | 85.2% | HTML_DELTA |
| `risultati-ricerca` | 200 | 200 | 73.3% | HTML_DELTA |
| `argomenti` | 200 | 200 | 80.7% | HTML_DELTA |
| `argomento` | 200 | 200 | 35.3% | HTML_DELTA |
| `lista-risorse` | 200 | 200 | 28.5% | HTML_DELTA |
| `lista-categorie` | 200 | 500 | 0.0% | LOCAL_500 |
| `lista-risorse-categorie` | 200 | 500 | 0.0% | LOCAL_500 |
| `mappa-sito` | 200 | 200 | 54.5% | HTML_DELTA |
| `amministrazione` | 200 | 200 | 37.8% | HTML_DELTA |
| `aree-amministrative` | 404 | 500 | 0.0% | LOCAL_500 |
| `area-amministrativa-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `organo` | 404 | 500 | 0.0% | LOCAL_500 |
| `persona` | 404 | 500 | 0.0% | LOCAL_500 |
| `persona-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `ufficio` | 404 | 500 | 0.0% | LOCAL_500 |
| `ufficio-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `enti-e-fondazioni` | 404 | 500 | 0.0% | LOCAL_500 |
| `ente-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `documenti-dati` | 200 | 500 | 0.0% | LOCAL_500 |
| `documento-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `dataset-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `novita` | 200 | 500 | 0.0% | LOCAL_500 |
| `novita-dettaglio` | 200 | 200 | 35.0% | HTML_DELTA |
| `servizi` | 200 | 500 | 0.0% | LOCAL_500 |
| `servizi-categoria` | 200 | 200 | 32.3% | HTML_DELTA |
| `servizio-dettaglio` | 200 | 500 | 0.0% | LOCAL_500 |
| `eventi` | 200 | 500 | 0.0% | LOCAL_500 |
| `evento-dettaglio` | 200 | 200 | 27.8% | HTML_DELTA |
| `luoghi` | 404 | 500 | 0.0% | LOCAL_500 |
| `luogo-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `contatti` | 404 | 200 | 0.0% | REF_404 |
| `pagamento` | 404 | 500 | 0.0% | LOCAL_500 |
| `pagamento-dettaglio` | 404 | 500 | 0.0% | LOCAL_500 |
| `prenotazione-appuntamento` | 404 | 500 | 0.0% | LOCAL_500 |
| `appuntamento-01-ufficio` | 200 | 200 | 59.3% | HTML_DELTA |
| `appuntamento-01-ufficio-luogo` | 200 | 200 | 55.3% | HTML_DELTA |
| `appuntamento-02-data-orario` | 200 | 200 | 53.3% | HTML_DELTA |
| `appuntamento-03-dettagli` | 200 | 200 | 56.1% | HTML_DELTA |
| `appuntamento-04-richiedente` | 200 | 200 | 55.2% | HTML_DELTA |
| `appuntamento-04-richiedente-autenticato` | 200 | 200 | 50.9% | HTML_DELTA |
| `appuntamento-05-riepilogo` | 200 | 200 | 57.8% | HTML_DELTA |
| `appuntamento-06-conferma` | 200 | 500 | 0.0% | LOCAL_500 |
| `richiesta-assistenza` | 404 | 500 | 0.0% | LOCAL_500 |
| `assistenza-01-dati` | 200 | 500 | 0.0% | LOCAL_500 |
| `assistenza-02-conferma` | 200 | 200 | 40.6% | HTML_DELTA |
| `segnalazione-disservizio` | 404 | 500 | 0.0% | LOCAL_500 |
| `segnalazione-dettaglio` | 200 | 200 | 25.8% | HTML_DELTA |
| `ticket-list` | 200 | 200 | 31.9% | HTML_DELTA |
| `segnalazione-01-privacy` | 200 | 200 | 89.3% | HTML_DELTA |
| `segnalazione-02-dati` | 200 | 200 | 47.8% | HTML_DELTA |
| `segnalazione-03-riepilogo` | 200 | 200 | 64.8% | HTML_DELTA |
| `segnalazione-04-conferma` | 200 | 200 | 41.9% | HTML_DELTA |
| `segnalazione-area-personale` | 200 | 200 | 23.4% | HTML_DELTA |

## Notes

- `READY_FOR_CSS_JS`: la gerarchia strutturale del `body` e abbastanza vicina per passare al refine visuale.
- `HTML_DELTA`: prima del CSS/JS serve riallineare la struttura HTML/CMS della pagina.
- `LOCAL_500` o altri status: la pagina locale non e ancora comparabile e va sbloccata.

## Artifacts

- Body raw/body files: [./batch-body-parity](./batch-body-parity)

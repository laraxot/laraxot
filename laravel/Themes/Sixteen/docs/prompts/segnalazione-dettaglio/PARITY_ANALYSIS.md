# HTML Parity Analysis - segnalazione-dettaglio

Questo file riassume la fase corrente. I punteggi storici precedenti non sono piu canonici perché il comparatore è stato rifatto.

## Canonical Run

Usare sempre:

```bash
bashscripts/html/html-structure-compare.sh \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html" \
  "http://127.0.0.1:8000/it/tests/segnalazione-dettaglio" \
  "segnalazione-dettaglio" \
  "laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/body-structure-comparison"
```

## Expected Outputs

- `body-structure-comparison/report.md`
- `body-structure-comparison/diff_details.json`
- `body-structure-comparison/reference-body.html`
- `body-structure-comparison/local-body.html`
- `body-structure-comparison/reference-structure.json`
- `body-structure-comparison/local-structure.json`

## Review Criteria

- priorita a struttura HTML semantica corretta
- corrispondenza di `id` e classi CSS
- nessun `script` o `style` nel confronto
- niente Bootstrap CSS/JS caricato
- stesso lessico strutturale della reference: `nav`, `ol`, `li`, `main`, `section`, `footer`, ecc.

## Follow-up

Dopo ogni run:
- leggere `report.md`
- correggere blade o JSON
- rilanciare il tool
- aggiornare questo documento solo con risultati prodotti dal comparatore canonico

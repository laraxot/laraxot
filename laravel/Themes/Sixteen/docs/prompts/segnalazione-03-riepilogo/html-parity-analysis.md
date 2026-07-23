# HTML Parity Analysis — segnalazione-03-riepilogo

**Date:** 2026-04-08
**Reference:** `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html`
**Local:** `http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo`
**Blade:** `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-03-riepilogo.blade.php`
**JSON:** `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-03-riepilogo.json`
**Tooling:** `bashscripts/html/html-structure-compare.sh` + `bashscripts/html/compare-html-body.py`

## Status

- Parity score: **92.05%**
- Tag parity: `99.44%`
- ID parity: `100.0%`
- Class parity: `99.83%`
- Reference elements: `532`
- Local elements: `541`
- Verdict: `PASS` rispetto alla soglia `90%`

## Canonical Command

```bash
bashscripts/html/html-structure-compare.sh \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html" \
  "http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo" \
  "segnalazione-03-riepilogo" \
  "laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison" \
  "90"
```

## Canonical Output

- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-comparison-report.md`
- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-comparison-report.json`
- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-reference-body.html`
- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-local-body.html`
- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-diff.txt`

## Residual Differences

Le differenze residue piu evidenti sono concentrate qui:
- nodi dentro `<head>`: `meta`, `title`, `link`
- attributi di asset e icone: `use`, `image`, `link`, alcuni `a`
- pochi nodi extra nel local: `9`
- nessun `id` mancante o extra

## Governance Notes

- `bashscripts` resta agnostico e non deve salvare output di progetto dentro `bashscripts/`
- gli output di questa fase vanno solo sotto `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/`
- la pagina test continua a passare da `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- le stringhe in blade devono restare via traduzioni a 5 livelli
- nel markup teniamo le classi Bootstrap Italia per parity HTML, ma senza caricare Bootstrap CSS/JS

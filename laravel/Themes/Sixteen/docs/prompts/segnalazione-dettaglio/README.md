# HTML Parity Analysis: `segnalazione-dettaglio`

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html`
- Local: `http://127.0.0.1:8000/it/tests/segnalazione-dettaglio`
- Target: `>= 90%` struttura HTML
- Status: `tooling aligned, parity run in progress`

## Canonical Files

- Blade: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- JSON: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json`
- Tool wrapper: `bashscripts/html/html-structure-compare.sh`
- Tool engine: `bashscripts/html/compare-html-body.py`
- Output dir: `laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/body-structure-comparison/`

## Rules Fixed For This Phase

- Non usare `laravel/Themes/Sixteen/resources/views/pages/tests/segnalazione-dettaglio.blade.php`.
- La layout corretta e `<x-layouts.app>`.
- `bashscripts` deve restare agnostico: nessun path del tema hardcoded negli script.
- Snapshot e report di progetto vanno in `laravel/Themes/Sixteen/docs/prompts/...`.
- Le blade non devono contenere testo hardcoded in italiano.
- Le chiavi traduzione devono usare 5 livelli: `fixcity::contesto.collezione.chiave.tipo`.
- Per la parity HTML manteniamo le classi Bootstrap Italia nel markup, ma non carichiamo Bootstrap CSS/JS.
- La resa visuale e funzionale resta TailwindCSS + Alpine.js.

## Current Focus

Questa fase serve prima di tutto a rendere affidabile il comparatore HTML:
- confronta body markup senza `script/style/noscript`
- confronta nodo per nodo tag, attributi, `id`, classi e testo inline
- genera `report.md` e `diff_details.json`
- salva output nel percorso canonico del tema

## Related Docs

- [`PARITY_ANALYSIS.md`](./PARITY_ANALYSIS.md)
- [`body-structure-comparison/`](./body-structure-comparison/)
- [`docs/html-structure-comparison.md`](../../../../../docs/html-structure-comparison.md)
- [`bashscripts/docs/HTML-COMPARISON.md`](../../../../../bashscripts/docs/HTML-COMPARISON.md)

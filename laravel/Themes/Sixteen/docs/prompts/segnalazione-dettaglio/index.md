# Segnalazione Dettaglio Prompt Index

## Obiettivo di Fase

- Raggiungere almeno il `90%` di parità strutturale HTML rispetto alla reference Design Comuni.
- Usare come riferimento il contenuto del tag `<body>`, escludendo `<script>`, `<style>`, `<noscript>` e commenti.
- Salvare tutti gli artefatti di questa pagina sotto la documentazione del tema, non dentro `bashscripts`.

## Confronto Attivo

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html`
- Local: `http://127.0.0.1:8000/it/tests/segnalazione-dettaglio`
- Script: `bashscripts/html/html-structure-compare.sh`
- Engine: `bashscripts/html/compare-html-body.py`
- Report output: `body-structure-comparison/`

## Regole Chiave

- La route usa `resources/views/pages/tests/[slug].blade.php`, non una blade dedicata per pagina.
- Il layout deve restare `<x-layouts.app>`.
- Nessuna stringa hardcoded in lingua nella blade.
- Formato traduzioni: `fixcity::contesto.collezione.chiave.tipo`.
- Nel markup vanno mantenuti tag semantici, `id` e classi Bootstrap della reference per la parità HTML.
- Bootstrap CSS/JS non va caricato: styling e comportamento restano `TailwindCSS + Alpine.js`.

## Artefatti Attesi

- `body-structure-comparison/reference-body.html`
- `body-structure-comparison/local-body.html`
- `body-structure-comparison/reference-elements.json`
- `body-structure-comparison/local-elements.json`
- `body-structure-comparison/comparison-report.json`
- `body-structure-comparison/comparison-report.md`
- `body-structure-comparison/parity-summary.txt`
- `body-structure-comparison/body.diff` quando richiesto

## Collegamenti

- Docs script: `bashscripts/docs/html/compare-html.md`
- Indice bashscripts: `bashscripts/docs/index.md`
- Entry page: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- JSON contenuti: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json`

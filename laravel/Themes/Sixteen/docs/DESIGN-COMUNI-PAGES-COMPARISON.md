# Design Comuni Pages - HTML Comparison Report

## Overview

This document tracks the HTML structural comparison between reference pages from
https://italia.github.io/design-comuni-pagine-statiche/sito/ and our local test pages.

**Last Updated:** 2026-05-29 10:38:07

## Methodology

1. Extract `<body>` content from both reference and local pages
2. Remove `<script>` tags from comparison
3. Tokenize HTML (tags, IDs, classes, data attributes, hrefs, inputs)
4. Calculate Jaccard similarity (intersection/union)
5. Document structural differences (divs, anchors, inputs)

## Results Summary

| Page | Similarity | Status | Reference Structure | Local Structure |
|------|------------|--------|---------------------|-----------------|
<<<<<<< HEAD
=======
<<<<<<< Updated upstream
| segnalazione-02-dati | 78% ⚠️ | OK | 147divs,91anchors,3inputs | 133divs,90anchors,3inputs |
| homepage | 73% ⚠️ | OK | 215divs,139anchors,18inputs | 186divs,139anchors,8inputs |
| argomenti | 67% ⚠️ | OK | 230divs,108anchors,17inputs | 185divs,102anchors,6inputs |
| segnalazione-dettaglio | 64% ⚠️ | OK | 186divs,121anchors,17inputs | 130divs,108anchors,6inputs |
| amministrazione | 63% ⚠️ | OK | 173divs,94anchors,17inputs | 110divs,89anchors,6inputs |
| ticket-list | 62% ⚠️ | OK | 261divs,89anchors,28inputs | 315divs,89anchors,28inputs |
| segnalazione-04-conferma | 58% ⚠️ | OK | 128divs,88anchors,17inputs | 105divs,86anchors,6inputs |
| domande-frequenti | 57% ⚠️ | OK | 261divs,84anchors,18inputs | 211divs,80anchors,7inputs |
| argomento | 56% ⚠️ | OK | 229divs,116anchors,17inputs | 100divs,82anchors,6inputs |
| lista-categorie | 55% ⚠️ | OK | 187divs,100anchors,17inputs | 95divs,83anchors,1inputs |
| segnalazione-01-privacy | 54% ⚠️ | OK | 94divs,86anchors,2inputs | 80divs,80anchors,1inputs |
| assistenza-01-dati | 52% ⚠️ | OK | 133divs,87anchors,5inputs | 88divs,80anchors,7inputs |
| novita | 50% ⚠️ | OK | 211divs,111anchors,18inputs | 92divs,86anchors,1inputs |
| eventi | 50% ⚠️ | OK | 228divs,123anchors,17inputs | 95divs,86anchors,1inputs |
| documenti-dati | 49% ❌ | OK | 210divs,108anchors,18inputs | 91divs,89anchors,1inputs |
| servizi-categoria | 49% ❌ | OK | 183divs,102anchors,18inputs | 94divs,84anchors,1inputs |
| lista-risorse | 48% ❌ | OK | 197divs,108anchors,18inputs | 171divs,94anchors,18inputs |
| lista-risorse-categorie | 47% ❌ | OK | 279divs,128anchors,18inputs | 88divs,84anchors,1inputs |
| appuntamento-05-riepilogo | 46% ❌ | OK | 156divs,88anchors,1inputs | 80divs,80anchors,1inputs |
| appuntamento-03-dettagli | 45% ❌ | OK | 122divs,85anchors,1inputs | 80divs,80anchors,1inputs |
| appuntamento-01-ufficio-luogo | 44% ❌ | OK | 136divs,84anchors,4inputs | 80divs,80anchors,1inputs |
| appuntamento-04-richiedente | 44% ❌ | OK | 121divs,84anchors,4inputs | 80divs,80anchors,1inputs |
| appuntamento-04-richiedente-autenticato | 43% ❌ | OK | 132divs,85anchors,1inputs | 80divs,80anchors,1inputs |
| segnalazione-03-riepilogo | 43% ❌ | OK | 149divs,87anchors,1inputs | 80divs,80anchors,1inputs |
| assistenza-02-conferma | 42% ❌ | OK | 129divs,83anchors,17inputs | 80divs,80anchors,1inputs |
| appuntamento-01-ufficio | 40% ❌ | OK | 115divs,84anchors,1inputs | 107divs,79anchors,5inputs |
| segnalazione-area-personale | 38% ❌ | OK | 253divs,134anchors,3inputs | 90divs,83anchors,1inputs |
| appuntamento-02-data-orario | 37% ❌ | OK | 138divs,85anchors,4inputs | 92divs,79anchors,1inputs |
| servizi | 36% ❌ | OK | 221divs,116anchors,18inputs | 83divs,85anchors,1inputs |
| servizio-dettaglio | 36% ❌ | OK | 208divs,126anchors,17inputs | 127divs,86anchors,1inputs |
| evento-dettaglio | 35% ❌ | OK | 220divs,129anchors,17inputs | 84divs,81anchors,1inputs |
| novita-dettaglio | 33% ❌ | OK | 158divs,101anchors,17inputs | 83divs,85anchors,1inputs |
=======
>>>>>>> laraxot/dev
| homepage | 76% ⚠️ | OK | 215divs,139anchors,18inputs | 243divs,140anchors,18inputs |
| argomenti | 73% ⚠️ | OK | 230divs,108anchors,17inputs | 229divs,104anchors,17inputs |
| amministrazione | 70% ⚠️ | OK | 173divs,94anchors,17inputs | 155divs,89anchors,17inputs |
| segnalazione-04-conferma | 68% ⚠️ | OK | 128divs,88anchors,17inputs | 143divs,89anchors,17inputs |
| segnalazioni-elenco | 68% ⚠️ | OK | 261divs,89anchors,28inputs | 243divs,88anchors,23inputs |
| domande-frequenti | 63% ⚠️ | OK | 261divs,84anchors,18inputs | 255divs,82anchors,18inputs |
| argomento | 62% ⚠️ | OK | 229divs,116anchors,17inputs | 143divs,84anchors,17inputs |
| segnalazione-dettaglio | 56% ⚠️ | OK | 186divs,121anchors,17inputs | 162divs,99anchors,17inputs |
| lista-categorie | 47% ❌ | OK | 187divs,100anchors,17inputs | 96divs,85anchors,1inputs |
| lista-risorse | 46% ❌ | OK | 197divs,108anchors,18inputs | 175divs,105anchors,18inputs |
| assistenza-01-dati | 44% ❌ | OK | 133divs,87anchors,5inputs | 89divs,82anchors,7inputs |
| novita | 43% ❌ | OK | 211divs,111anchors,18inputs | 93divs,88anchors,1inputs |
| eventi | 43% ❌ | OK | 228divs,123anchors,17inputs | 96divs,88anchors,1inputs |
| documenti-dati | 42% ❌ | OK | 210divs,108anchors,18inputs | 92divs,91anchors,1inputs |
| servizi-categoria | 42% ❌ | OK | 183divs,102anchors,18inputs | 95divs,86anchors,1inputs |
| lista-risorse-categorie | 40% ❌ | OK | 279divs,128anchors,18inputs | 89divs,86anchors,1inputs |
| appuntamento-01-ufficio | 40% ❌ | OK | 115divs,84anchors,1inputs | 105divs,81anchors,5inputs |
| appuntamento-02-data-orario | 38% ❌ | OK | 138divs,85anchors,4inputs | 90divs,81anchors,1inputs |
| appuntamento-03-dettagli | 38% ❌ | OK | 122divs,85anchors,1inputs | 80divs,82anchors,1inputs |
| appuntamento-05-riepilogo | 38% ❌ | OK | 156divs,88anchors,1inputs | 80divs,82anchors,1inputs |
| appuntamento-01-ufficio-luogo | 37% ❌ | OK | 136divs,84anchors,4inputs | 80divs,82anchors,1inputs |
| appuntamento-04-richiedente | 37% ❌ | OK | 121divs,84anchors,4inputs | 80divs,82anchors,1inputs |
| appuntamento-04-richiedente-autenticato | 36% ❌ | OK | 132divs,85anchors,1inputs | 80divs,82anchors,1inputs |
| assistenza-02-conferma | 36% ❌ | OK | 129divs,83anchors,17inputs | 80divs,82anchors,1inputs |
| servizio-dettaglio | 32% ❌ | OK | 208divs,126anchors,17inputs | 128divs,88anchors,1inputs |
| servizi | 31% ❌ | OK | 221divs,116anchors,18inputs | 83divs,87anchors,1inputs |
| evento-dettaglio | 30% ❌ | OK | 220divs,129anchors,17inputs | 85divs,83anchors,1inputs |
| novita-dettaglio | 29% ❌ | OK | 158divs,101anchors,17inputs | 83divs,87anchors,1inputs |
| segnalazione-01-privacy | 0% ❌ | ERROR | 0divs,0anchors,0inputs | 0divs,0anchors,0inputs |
| segnalazione-02-dati | 0% ❌ | ERROR | 0divs,0anchors,0inputs | 0divs,0anchors,0inputs |
| segnalazione-03-riepilogo | 0% ❌ | ERROR | 0divs,0anchors,0inputs | 0divs,0anchors,0inputs |
| segnalazione-area-personale | 0% ❌ | ERROR | 0divs,0anchors,0inputs | 0divs,0anchors,0inputs |
<<<<<<< HEAD
=======
>>>>>>> Stashed changes
>>>>>>> laraxot/dev

## Overall Statistics

- **Total Pages Analyzed:** 32
- **Average Similarity:** 47%

- **High Similarity (≥80%):** 0 pages
- **Medium Similarity (50-79%):** 8 pages
- **Low Similarity (<50%):** 24 pages

## Notes

- Similarity is calculated using Jaccard similarity on tokenized HTML
- Tokens include: HTML tags, IDs, classes, data attributes, hrefs, input names/types
- Scripts are excluded from comparison

## Action Items

- Pages with similarity < 50% require HTML structure review
- Pages with similarity 50-79% need HTML structure and CSS fixes
- Pages with similarity ≥ 80% need only CSS/JS visual fixes

---

*Generated by compare-html-similarity.py*

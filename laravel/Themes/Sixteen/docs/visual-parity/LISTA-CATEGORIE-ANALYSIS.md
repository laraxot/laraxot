# Visual Parity Analysis - lista-categorie

**Data**: 2026-04-04
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html
**Local**: http://127.0.0.1:8000/it/tests/lista-categorie

## Struttura HTML

### Tag Count Comparison

| Tag | Reference | Local | Diff |
|-----|-----------|-------|------|
| div | 187 | 95 | -92 |
| a | 100 | 83 | -17 |
| li | 76 | 71 | -5 |
| span | 56 | 37 | -19 |
| svg | 40 | 30 | -10 |
| ul | 16 | 14 | -2 |
| h3 | 15 | 3 | -12 |
| p | 14 | 8 | -6 |

## Differenze Classi CSS

### Classi Mancanti in Local (non usate ma disponibili in Bootstrap Italia)
- `border-0`, `text-wrap`, `button-shadow`, `form-rating`
- `pt-4`, `btn-outline-primary`, `row-shadow`
- `card-teaser`, `cmp-rating__card-second`, `step`
- `cmp-radio-list`, `cmp-steps-rating`

### Classi Extra in Local (Tailwind)
- `small`, `text-muted`, `col-lg-4`, `mt-2`

## Principali Differenze Visive da Correggere

1. **Hero Section**: Differenza nell'allineamento del testo
2. **Cards Grid**: Spaziatura e layout delle card
3. **Footer**: Struttura dei link e icone social
4. **Category Cards**: Titoli e descrizioni

## Action Plan

1. [ ] Verificare che il build CSS sia corretto
2. [ ] Analizzare differenze Hero section
3. [ ] Analizzare differenze Category cards
4. [ ] Analizzare differenze Footer
5. [ ] Implementare CSS fixes

## File Utili

- HTML: `temp/reference-lista-categorie.html`, `temp/local-lista-categorie.html`
- Report: `temp/comparison-report.json`
- Screenshot: `screenshots/`
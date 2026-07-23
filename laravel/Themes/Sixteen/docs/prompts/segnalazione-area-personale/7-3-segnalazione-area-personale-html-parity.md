# Story 7.3: HTML Parity — segnalazione-area-personale

Status: ready-for-dev

## Story

Come **sviluppatore** che lavora sulla pagina `segnalazione-area-personale`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
- **Locale**: http://127.0.0.1:8000/it/tests/segnalazione-area-personale
- **Parity attuale**: **43.1%** (rilevato 2026-04-10) — lavoro significativo necessario
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-area-personale/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA** blade, non creare blade per-pagina)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-area-personale.json`

## Acceptance Criteria

1. **Parity ≥ 90%**: lo script `bashscripts/html/compare-html.sh` restituisce ≥90% di similitudine strutturale
2. **HTML semantico identico**: stessi tag (`nav`, `ol`, `li`, `main`, `section`, `article`, ecc.) come il riferimento
3. **Stesse classi CSS**: le classi Bootstrap presenti nel riferimento sono presenti anche nel locale (es. `row`, `col-12`, `btn`, `card`)
4. **Stessi ID**: gli ID chiave del riferimento sono presenti nel locale
5. **Nessun testo hardcoded**: tutte le stringhe italiane passano per traduzioni 5-livelli (`fixcity::namespace.collection.element.type`)
6. **Nessuna regressione**: le altre pagine `tests/*` continuano a funzionare
7. **Report aggiornato**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-area-personale/report.md` riflette il parity score finale

## Tasks / Subtasks

- [ ] Eseguire baseline: `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html" "http://127.0.0.1:8000/it/tests/segnalazione-area-personale" "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-area-personale"`
- [ ] Analizzare `report.md` generato: identificare elementi mancanti, ID mancanti, tag diversi
- [ ] Aggiornare JSON (`tests.segnalazione-area-personale.json`) con i blocchi necessari
- [ ] Creare/aggiornare blade dei blocchi in `laravel/Themes/Sixteen/resources/views/components/blocks/`
- [ ] Aggiungere/correggere traduzioni in `laravel/Modules/Fixcity/lang/it/segnalazione.php`
- [ ] Re-eseguire script, verificare ≥90%
- [ ] Aggiornare indice `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-area-personale.json` |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| Traduzioni Fixcity | `laravel/Modules/Fixcity/lang/it/segnalazione.php` |
| Script confronto | `bashscripts/html/compare-html.sh` |
| Output report | `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-area-personale/` |

### ⛔ Regole CRITICHE — errori del passato da NON ripetere

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/segnalazione-area-personale.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| Layout `laravel/Themes/Sixteen/resources/views/layouts/design-comuni.blade.php` | Non deve esistere — usare `layouts/app.blade.php` |
| `fixcity::segnalazione.heading.title_label` | `fixcity::segnalazione.heading.title.label` (5 livelli) |
| `SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE` | `fixcity::segnalazione.fields.title.label` |
| `<link … bootstrap-italia.min.css>` o CDN Bootstrap | MAI — usiamo TailwindCSS @apply |
| `<script … bootstrap-italia.bundle.min.js>` | MAI — usiamo Alpine.js |
| NO classi Bootstrap nell'HTML | ✅ Classi Bootstrap SI nell'HTML (per parity), CSS Bootstrap NO |
| Tag `<div>` dove il riferimento usa `<nav>`, `<ol>`, `<li>` | Usare gli stessi tag semantici del riferimento |

### Formato traduzioni (5 livelli obbligatori)

```
fixcity::<contesto>.<collection>.<element>.<tipo>
```

Esempi corretti:
- `fixcity::segnalazione.fields.title.label`
- `fixcity::segnalazione.actions.submit.label`
- `fixcity::segnalazione.headings.area-personale.title.label`

### Come funziona [slug].blade.php

La blade `pages/tests/[slug].blade.php` carica i blocchi dal JSON tramite `Page::getBlocksBySlug()`. NON contiene logica specifica per pagina. Per aggiungere contenuto, modificare il JSON e i blocchi componente.

### bashscripts è agnostico

Lo script `bashscripts/html/compare-html.sh` non conosce il progetto. Accetta `<url1> <url2> [output_dir]`. L'output va sempre in `laravel/Themes/Sixteen/docs/body-structure-comparison/<pagina>/`.

### Story CSS/JS dipendente

Quando questa story raggiunge parity ≥90% (o almeno ≥80%), può iniziare la story **7-10** (CSS/JS per segnalazione-area-personale).

## Dev Agent Record

### Agent Model Used

### Debug Log References

### Completion Notes List

### File List

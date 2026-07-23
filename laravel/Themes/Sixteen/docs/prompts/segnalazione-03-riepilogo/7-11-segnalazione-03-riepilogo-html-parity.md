# Story 7.11: HTML Parity — segnalazione-03-riepilogo

Status: ready-for-dev

## Story

Come **sviluppatore** che lavora sulla pagina `segnalazione-03-riepilogo`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html
- **Locale**: http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo
- **Parity attuale**: **68.6%** (rilevato 2026-04-10) — lavoro moderato necessario
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-03-riepilogo/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA** blade, non creare blade per-pagina)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-03-riepilogo.json`
- **Nota**: questa è la pagina di **riepilogo** (step 3 del flusso segnalazione) — mostra tutti i dati inseriti prima della conferma

## Acceptance Criteria

1. **Parity ≥ 90%**: lo script `bashscripts/html/compare-html.sh` restituisce ≥90% di similitudine strutturale
2. **HTML semantico identico**: stessi tag (`nav`, `ol`, `li`, `main`, `section`, `dl`, `dt`, `dd`, ecc.) come il riferimento
3. **Stesse classi CSS**: le classi Bootstrap presenti nel riferimento sono presenti anche nel locale (`row`, `col-12`, `btn`, `card`, ecc.)
4. **Stessi ID**: gli ID chiave del riferimento sono presenti nel locale
5. **Stepper di navigazione**: struttura dello stepper identica al riferimento (indica step 3/4 o 3/3)
6. **Riepilogo dati**: struttura `<dl>/<dt>/<dd>` per mostrare i dati inseriti nel passo precedente
7. **Bottoni Indietro/Conferma**: stessa struttura HTML e classi del riferimento
8. **Nessun testo hardcoded**: tutte le stringhe italiane passano per traduzioni 5-livelli (`fixcity::namespace.collection.element.type`)
9. **Nessuna regressione**: le altre pagine `tests/*` continuano a funzionare
10. **Report aggiornato**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-03-riepilogo/report.md` riflette il parity score finale

## Tasks / Subtasks

- [ ] Eseguire baseline: `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html" "http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo" "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-03-riepilogo"`
- [ ] Analizzare `report.md` generato: identificare elementi mancanti, ID mancanti, tag diversi
- [ ] Confrontare HTML reference con local per identificare sezioni intere mancanti (stepper, riepilogo-dati, pulsanti)
- [ ] Aggiornare JSON (`tests.segnalazione-03-riepilogo.json`) con i blocchi necessari
- [ ] Creare/aggiornare blade dei blocchi in `laravel/Themes/Sixteen/resources/views/components/blocks/`
- [ ] Aggiungere/correggere traduzioni in `laravel/Modules/Fixcity/lang/it/segnalazione.php` e `en/segnalazione.php`
- [ ] Re-eseguire script, verificare ≥90%
- [ ] Aggiornare indice `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-03-riepilogo.json` |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| Traduzioni IT | `laravel/Modules/Fixcity/lang/it/segnalazione.php` |
| Traduzioni EN | `laravel/Modules/Fixcity/lang/en/segnalazione.php` |
| Script confronto | `bashscripts/html/compare-html.sh` |
| Output report | `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-03-riepilogo/` |

### ⛔ Regole CRITICHE — errori del passato da NON ripetere

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/segnalazione-03-riepilogo.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| Layout `laravel/Themes/Sixteen/resources/views/layouts/design-comuni.blade.php` | Non deve esistere — usare `layouts/app.blade.php` |
| `fixcity::segnalazione.riepilogo.title_text` | `fixcity::segnalazione.riepilogo.title.text` (5 livelli, punto) |
| `SEGNALAZIONE::SEGNALAZIONE.RIEPILOGO.TITLE` | `fixcity::segnalazione.riepilogo.title.label` |
| `<link … bootstrap-italia.min.css>` o CDN Bootstrap | MAI — usiamo TailwindCSS @apply |
| `<script … bootstrap-italia.bundle.min.js>` | MAI — usiamo Alpine.js |
| NO classi Bootstrap nell'HTML | ✅ Classi Bootstrap SI nell'HTML (per parity), CSS Bootstrap NO |
| Tag `<div>` dove il riferimento usa `<dl>`, `<dt>`, `<dd>` | Usare gli stessi tag semantici del riferimento |

### Formato traduzioni (5 livelli obbligatori)

```
fixcity::<contesto>.<collection>.<element>.<tipo>
```

Esempi corretti:
- `fixcity::segnalazione.riepilogo.title.label`
- `fixcity::segnalazione.riepilogo.subtitle.text`
- `fixcity::segnalazione.actions.back.label`
- `fixcity::segnalazione.actions.confirm.label`
- `fixcity::segnalazione.fields.summary.label`

### Come funziona [slug].blade.php

La blade `pages/tests/[slug].blade.php` carica i blocchi dal JSON tramite `Page::getBlocksBySlug()`. NON contiene logica specifica per pagina. Per aggiungere contenuto, modificare il JSON e i blocchi componente.

### bashscripts è agnostico

Lo script `bashscripts/html/compare-html.sh` non conosce il progetto. Accetta `<url1> <url2> [output_dir]`. L'output va sempre in `laravel/Themes/Sixteen/docs/body-structure-comparison/<pagina>/`.

### Story CSS/JS dipendente

Quando questa story raggiunge parity ≥80% (preferibilmente ≥90%), può iniziare la story **7-19** (CSS/JS per segnalazione-03-riepilogo).

## Dev Agent Record

### Agent Model Used

### Debug Log References

### Completion Notes List

### File List

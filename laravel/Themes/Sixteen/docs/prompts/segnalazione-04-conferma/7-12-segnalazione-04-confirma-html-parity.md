# Story 7.12: HTML Parity — segnalazione-04-conferma

Status: ready-for-dev

## Story

Come **sviluppatore** che lavora sulla pagina `segnalazione-04-conferma`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html
- **Locale**: http://127.0.0.1:8000/it/tests/segnalazione-04-conferma
- **Parity attuale**: **44.6%** (rilevato 2026-04-10) — lavoro significativo necessario (footer mancante, data-element 9 vs 37)
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-04-conferma/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA** blade, non creare blade per-pagina)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-04-confirma.json`
- **Nota**: questa è la pagina di **conferma finale** (step 4/4 del flusso segnalazione) — mostra un messaggio di successo dopo l'invio. È una **pagina separata** (redirect post-submit), NON uno step inline del wizard.

## Acceptance Criteria

1. **Parity ≥ 90%**: lo script `bashscripts/html/compare-html.sh` restituisce ≥90%
2. **HTML semantico identico**: stessi tag (`main`, `section`, `article`, ecc.) come il riferimento
3. **Stesse classi CSS**: le classi Bootstrap del riferimento presenti nel locale
4. **Stessi ID**: gli ID chiave del riferimento presenti nel locale
5. **Messaggio conferma**: struttura HTML del messaggio di successo identica al riferimento (icona, titolo, descrizione, CTA)
6. **Link/Bottoni CTA**: stessa struttura HTML per tornare alla home o alle segnalazioni
7. **Nessun testo hardcoded**: tutte le stringhe tramite traduzioni 5-livelli
8. **Nessuna regressione**: le altre pagine `tests/*` continuano a funzionare
9. **Report aggiornato**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-04-conferma/report.md`

## Tasks / Subtasks

- [ ] Eseguire baseline: `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html" "http://127.0.0.1:8000/it/tests/segnalazione-04-conferma" "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-04-conferma"`
- [ ] Analizzare report: identificare elementi mancanti, ID mancanti, tag diversi
- [ ] Identificare struttura completa della pagina conferma nel riferimento (icona successo, titolo, paragrafo, bottoni)
- [ ] Aggiornare JSON (`tests.segnalazione-04-confirma.json`) con i blocchi necessari
- [ ] Creare/aggiornare blade dei blocchi in `laravel/Themes/Sixteen/resources/views/components/blocks/`
- [ ] Aggiungere/correggere traduzioni in `laravel/Modules/Fixcity/lang/it/segnalazione.php` e `en/segnalazione.php`
- [ ] Re-eseguire script, verificare ≥90%
- [ ] Aggiornare indice `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-04-confirma.json` |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| Traduzioni IT | `laravel/Modules/Fixcity/lang/it/segnalazione.php` |
| Traduzioni EN | `laravel/Modules/Fixcity/lang/en/segnalazione.php` |
| Script confronto | `bashscripts/html/compare-html.sh` |
| Output report | `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-04-conferma/` |

### ⛔ Regole CRITICHE — errori del passato da NON ripetere

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/segnalazione-04-conferma.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| Fare questo step inline nel wizard | È una **pagina separata** con redirect (regola: wizard confirm è pagina separata) |
| `<link … bootstrap-italia.min.css>` o CDN Bootstrap | MAI — usiamo TailwindCSS @apply |
| `<script … bootstrap-italia.bundle.min.js>` | MAI — usiamo Alpine.js |
| NO classi Bootstrap nell'HTML | ✅ Classi Bootstrap SI nell'HTML (per parity), CSS Bootstrap NO |
| Testo italiano hardcoded | `fixcity::segnalazione.conferma.title.label` (5 livelli) |

### Formato traduzioni (5 livelli obbligatori)

```
fixcity::<contesto>.<collection>.<element>.<tipo>
```

Esempi corretti:
- `fixcity::segnalazione.conferma.title.label`
- `fixcity::segnalazione.conferma.subtitle.text`
- `fixcity::segnalazione.conferma.cta-home.label`
- `fixcity::segnalazione.conferma.description.text`

### Pagina separata (non step wizard)

Come da regola consolidata nel progetto: il passo conferma nel wizard è **sempre un redirect a una pagina separata** (`*-04-conferma`), mai uno step inline. Questa pagina è una view standalone raggiunta via redirect dopo il submit del form.

### Come funziona [slug].blade.php

La blade `pages/tests/[slug].blade.php` carica i blocchi dal JSON tramite `Page::getBlocksBySlug()`. NON contiene logica specifica per pagina.

### bashscripts è agnostico

Lo script `bashscripts/html/compare-html.sh` non conosce il progetto. Accetta `<url1> <url2> [output_dir]`. L'output va in `laravel/Themes/Sixteen/docs/body-structure-comparison/<pagina>/`.

### Story CSS/JS dipendente

Quando questa story raggiunge parity ≥80%, può iniziare la story **7-20** (CSS/JS per segnalazione-04-conferma).

## Dev Agent Record

### Agent Model Used

### Debug Log References

### Completion Notes List

### File List

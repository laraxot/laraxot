# Story 7.4: HTML Parity — ticket-list

Status: ready-for-dev

## Story

Come **sviluppatore** che lavora sulla pagina `ticket-list`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
- **Locale**: http://127.0.0.1:8000/it/tests/ticket-list
- **Parity attuale**: **77.8%** (603/775 elementi identici) — rilevato 2026-04-08
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA**, non creare blade per-pagina)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json`

## Situazione attuale (da report 2026-04-08)

| Metrica | Valore |
|---------|--------|
| Elementi reference | 775 |
| Elementi local | 726 |
| ✅ Identici | 603 (77.8%) |
| ⚠️ Diversi | 68 |
| ❌ Mancanti in local | 104 |
| ➕ Extra in local | 55 |
| ID comuni | 29 |
| ID mancanti in local | 20 |
| Classi mancanti in local | ~37 |

**Già sopra la soglia 80%** per CSS/JS (story 7-11 può partire in parallelo), ma dobbiamo arrivare a ≥90%.

## Acceptance Criteria

1. **Parity ≥ 90%**: `compare-html.sh` restituisce ≥90%
2. **ID mancanti risolti**: i 20 ID mancanti (`fifth-star`, ecc.) aggiunti dove appropriato
3. **HTML semantico identico**: stessi tag del riferimento (`nav`, `ol`, `li`, `section`, ecc.)
4. **Classi CSS identiche**: le ~37 classi mancanti aggiunte nei blocchi
5. **Extra in local ridotti**: i 55 elementi extra verificati e rimossi se non necessari
6. **Nessun testo hardcoded**: solo traduzioni 5-livelli (`fixcity::namespace.collection.element.type`)
7. **Nessuna regressione** sulle altre pagine tests

## Tasks / Subtasks

- [ ] Rileggere report baseline: `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/report.md`
- [ ] Identificare i 20 ID mancanti e dove aggiungerli nel JSON/blocchi
- [ ] Correggere struttura HTML dei blocchi per aggiungere classi mancanti
- [ ] Rimuovere elementi extra (55) se non presenti nel riferimento
- [ ] Aggiornare traduzioni 5-livelli per eventuali nuovi testi
- [ ] Re-eseguire: `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html" "http://127.0.0.1:8000/it/tests/ticket-list" "laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list"`
- [ ] Verificare ≥90%; iterare se necessario
- [ ] Aggiornare `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json` (nota: plurale con 'i') |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| CSS parity | `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` |
| Script confronto | `bashscripts/html/compare-html.sh` |
| Report | `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/` |

### ⛔ Regole CRITICHE — errori passati da NON ripetere

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/ticket-list.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| `fixcity::segnalazione.heading.title_label` | `fixcity::segnalazione.heading.title.label` (punto, non underscore) |
| CDN Bootstrap o asset Bootstrap locale | MAI — TailwindCSS @apply |
| NO Bootstrap class names nell'HTML | ✅ Bootstrap class NAMES sì (per parity), file CSS/JS Bootstrap NO |
| `<div>` dove reference usa `<nav>`, `<ol>` | Stesso tag semantico del riferimento |

### Formato traduzioni (5 livelli)

```
fixcity::<contesto>.<collection>.<element>.<tipo>
```
- `fixcity::segnalazione.listing.title.label`
- `fixcity::segnalazione.filters.category.label`

### Note sul JSON `ticket-list`

Il filename JSON è `tests.ticket-list.json` (plurale con i, diverso dalle altre pagine).

## Dev Agent Record

### Agent Model Used

### Debug Log References

### Completion Notes List

### File List

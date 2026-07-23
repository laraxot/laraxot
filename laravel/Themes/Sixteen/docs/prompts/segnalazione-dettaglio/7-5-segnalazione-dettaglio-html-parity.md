# Story 7.5: HTML Parity — segnalazione-dettaglio

Status: in-progress

## Story

Come **sviluppatore** che lavora sulla pagina `segnalazione-dettaglio`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html
- **Locale**: http://127.0.0.1:8000/it/tests/segnalazione-dettaglio
- **Parity attuale**: **45.5%** (366/804 elementi identici) — rilevato 2026-04-08 — **lavoro significativo necessario**
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA**)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json`

## Situazione attuale (da report 2026-04-08)

| Metrica | Valore |
|---------|--------|
| Elementi reference | 804 |
| Elementi local | 509 |
| ✅ Identici | 366 (45.5%) |
| ⚠️ Diversi | 134 |
| ❌ Mancanti in local | 304 |
| ➕ Extra in local | 9 |
| ID comuni | 10 |
| ID mancanti in local | 35 |
| Classi mancanti | 99 |

**Parity bassa (45.5%)**: questa pagina richiede un refactor strutturale significativo dei blocchi.

## Acceptance Criteria

1. **Parity ≥ 90%**: da 45.5% a ≥90%
2. **304 elementi mancanti risolti**: aggiungere sezioni/blocchi mancanti nel JSON e blade
3. **35 ID mancanti aggiunti**: tutti gli ID del riferimento presenti nel locale
4. **99 classi mancanti aggiunte**: struttura CSS class identica al riferimento
5. **134 elementi diversi corretti**: attributi, tag, ecc. allineati
6. **HTML semantico**: stessi tag del riferimento (`nav`, `aside`, `article`, `section`, ecc.)
7. **Nessun testo hardcoded in italiano**
8. **Nessuna regressione** su altre pagine

## Tasks / Subtasks

- [x] Analizzare in dettaglio `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/report.md`
- [ ] Confrontare HTML reference vs local per identificare sezioni intere mancanti
- [ ] Scaricare reference HTML: `curl -sL "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html" > /tmp/ref.html`
- [ ] Aggiornare/creare blocchi per le sezioni mancanti
- [ ] Aggiornare `tests.segnalazione-dettaglio.json` con i blocchi aggiunti
- [ ] Correggere attributi diversi (xlink:href → href per SVG `<use>`, ecc.)
- [ ] Aggiungere traduzioni 5-livelli per tutti i testi nuovi
- [ ] Re-eseguire script, verificare progressi
- [ ] `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html" "http://127.0.0.1:8000/it/tests/segnalazione-dettaglio" "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio"`
- [ ] Iterare fino a ≥90%
- [ ] Aggiornare `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json` |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| Comparison report | `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/report.md` |
| Script confronto | `bashscripts/html/compare-html.sh` |

### ⛔ Regole CRITICHE

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/segnalazione-dettaglio.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| Classi PHP con "Segnalazione" nel nome | Solo "Ticket" nelle classi PHP |
| Testo italiano hardcoded | `fixcity::segnalazione.fields.stato.label` (5 livelli) |
| Bootstrap CSS/JS caricato | MAI — TailwindCSS @apply |
| `xlink:href` in SVG (vecchio standard) | `href` (HTML5 standard moderno) |
| Tag `<div>` dove reference usa `<aside>`, `<article>` | Stesso tag semantico |

### Attenzione SVG

Il report mostra differenze su `<use xlink:href>` vs `<use href>`. Usare `href` (HTML5, senza namespace xlink).

### Strategia per parity bassa

Con 45.5%, mancano sezioni intere. Procedura consigliata:
1. Aprire il riferimento nel browser, ispezionare la struttura con DevTools
2. Identificare le macro-sezioni (header, breadcrumb, main-content, sidebar, footer, ecc.)
3. Per ogni sezione mancante: creare/adattare un blocco componente
4. Aggiornare il JSON per includere i blocchi

## Dev Agent Record

### Agent Model Used

Composer (agente dev-story), sessione 2026-04-20.

### Debug Log References

- `diff_details.json`: molti ID “mancanti” (es. `first-star`, `radio-1`) appartengono al blocco **feedback/rating** e al layout globale, non solo a `segnalazione-dettaglio.blade.php`.
- La baseline 45.5% confronta **intero `<body>`** (header, footer, modali, form rating) vs pagina locale: raggiungere ≥90% richiede allineamento coordinato di **layout app**, blocchi CMS e JSON, non un singolo file.

### Completion Notes List

- Allineati due `<use>` SVG nel blocco `tests/segnalazione-dettaglio`: `xlink:href` → `href` (standard HTML5, come da Dev Notes story).
- **HALT (workflow step 5–7)**: non è possibile marcare la story “review” senza verifica quantitativa. Serve `php artisan serve` (o URL locale raggiungibile) + `./bashscripts/html/compare-html.sh` per misurare parity dopo ogni iterazione. Stima lavoro residuo: centinaia di nodi DOM (report 304 mancanti + 134 diversi) su più partial/blocchi — fuori portata di una singola esecuzione agente senza spezzare la story o fornire server live.

### File List

- `laravel/Themes/Sixteen/resources/views/components/blocks/tests/ticket-detail.blade.php` (modificato)
- `_bmad-output/implementation-artifacts/sprint-status.yaml` (7-5 → in-progress, last_updated)

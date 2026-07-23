# Story 7.6: HTML Parity — segnalazione-01-privacy

Status: ready-for-dev

## Story

Come **sviluppatore** che lavora sulla pagina `segnalazione-01-privacy`,
voglio raggiungere almeno il **90% di HTML parity** rispetto al riferimento Design Comuni,
così che la struttura semantica sia corretta prima di lavorare su CSS/JS.

## Contesto

- **Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
- **Locale**: http://127.0.0.1:8000/it/tests/segnalazione-01-privacy
- **Parity attuale**: **84.0%** (rilevato 2026-04-10) — già sopra soglia 80% per CSS/JS!
- **Report baseline**: `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-01-privacy/report.md`
- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (**UNICA**)
- **JSON**: `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-01-privacy.json`
- **Nota**: questa pagina è il **step 1** del flusso segnalazione (privacy + checkbox accettazione)

## Acceptance Criteria

1. **Parity ≥ 90%**: script `compare-html.sh` restituisce ≥90%
2. **Struttura stepper** identica al riferimento (indicatore "1/3" o simile)
3. **Form privacy con checkbox**: `<input type="checkbox">` con classe Bootstrap + label "Ho letto l'informativa"
4. **Stessi tag semantici**: `<form>`, `<fieldset>`, `<legend>` dove il riferimento li usa
5. **Nessun testo hardcoded**: tutte le stringhe tramite traduzioni 5-livelli
6. **Bottone "Avanti"** con stessa struttura HTML del riferimento
7. **Nessuna regressione**

## Tasks / Subtasks

- [ ] Eseguire baseline: `./bashscripts/html/compare-html.sh "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html" "http://127.0.0.1:8000/it/tests/segnalazione-01-privacy" "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-01-privacy"`
- [ ] Analizzare report generato
- [ ] Identificare differenze strutturali: stepper, form, checkbox, pulsanti
- [ ] Aggiornare JSON e blocchi blade per allineare la struttura
- [ ] Verificare traduzioni 5-livelli per tutti i testi
- [ ] Re-eseguire script, iterare fino a ≥90%
- [ ] Aggiornare `laravel/Themes/Sixteen/docs/body-structure-comparison/INDEX.md`

## Dev Notes

### File caldi

| Area | Path |
|------|------|
| Blade tests (UNICA) | `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` |
| JSON pagina | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-01-privacy.json` |
| Blocchi tema | `laravel/Themes/Sixteen/resources/views/components/blocks/` |
| Traduzioni | `laravel/Modules/Fixcity/lang/it/segnalazione.php` |
| Script confronto | `bashscripts/html/compare-html.sh` |
| Output report | `laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-01-privacy/` |

### ⛔ Regole CRITICHE

| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| Creare `pages/tests/segnalazione-01-privacy.blade.php` | Usare SOLO `pages/tests/[slug].blade.php` |
| `<x-layouts.design-comuni>` | `<x-layouts.app>` |
| `fixcity::segnalazione.privacy.title_text` | `fixcity::segnalazione.privacy.title.text` (5 livelli, punto) |
| Bootstrap CSS/JS caricato | MAI |
| Classi PHP "Segnalazione*" | Solo "Ticket*" |
| Checkbox senza classi Bootstrap | Aggiungere `form-check-input`, `form-check-label` per parity |

### Nota su questa pagina vs wizard

La pagina `tests/segnalazione-01-privacy` è la **pagina statica di test** (non il wizard Livewire).
Il wizard è su `tests/segnalazione-crea` (stories 7-1/7-2). Non confondere le due cose.

### Formato traduzioni privacy

```php
fixcity::segnalazione.privacy.title.label
fixcity::segnalazione.privacy.description.text
fixcity::segnalazione.privacy.checkbox.label
fixcity::segnalazione.actions.next.label
```

## Dev Agent Record

### Agent Model Used

### Debug Log References

### Completion Notes List

### File List

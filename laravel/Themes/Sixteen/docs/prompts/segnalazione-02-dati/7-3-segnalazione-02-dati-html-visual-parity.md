# Story 7.3: segnalazione-02-dati — aumento HTML parity e visual parity

Status: review

## Story

Come **responsabile qualità frontoffice**,
voglio **avvicinare la pagina test `segnalazione-02-dati`** al riferimento **Design Comuni** (HTML semantico + aspetto visivo),
così che **layout, tipografia, stepper, form, card e footer di pagina** siano confrontabili con il modello statico e **riducano il gap** rispetto all'analisi strutturale già presente nel tema.

## Contesto

- **Pagina locale:** `http://127.0.0.1:8000/it/tests/segnalazione-02-dati`
- **Riferimento ufficiale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
- **Contenuto CMS:** `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-02-dati.json`
- **Blocco tema:** `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php`
- **CSS parity esistente:** sezione **§27** in `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` (righe ~3104-3297)
- **Analisi HTML baseline:** `laravel/Themes/Sixteen/docs/visual-comparison/structure-analysis/segnalazione-02-dati-html-comparison.md` — **~81%** similarità strutturale

## ⚠️ PROBLEMA CRITICO: Selettori CSS §27 non corrispondono al blade

**Il CSS §27 attuale usa selettori che NON esistono nel blade HTML.** Questo è il gap principale da risolvere: gli stili non vengono applicati.

### Mismatch rilevati (da correggere per priorità)

| Regola CSS §27 | Selettore CSS attuale (SBAGLIATO) | Selettore blade REALE |
|---|---|---|
| 27.3 Section cards | `.section-card`, `.form-section` | `.cmp-card .card.has-bkg-grey.shadow-sm` |
| 27.4 Section headings | `.section-card h2`, `.form-section h2` | `.cmp-card .card h2.title-xxlarge` |
| 27.6 Indietro button | `.btn-back` | `.steppers-btn-prev` |
| 27.7 Salva button | `.btn-save` (o `.saveBtn`) | `.steppers-btn-save` / `.saveBtn` |
| 27.8 Avanti button | `.btn-next` | `.steppers-btn-confirm` |
| 27.10 Upload button | `.btn-file-upload` | `.btn.btn-primary.w-100.fw-bold` (nel `#report-info`) |
| 27.14 File thumbnail | `.file-thumbnail` | `.upload-wrapper` |
| 27.15 User card | `.user-card` | `.cmp-info-button-card .card` |

### Struttura reale del blade (leggere prima di toccare CSS)

```
#main-container
  .container > .row
    .col-12.col-lg-10        ← heading + breadcrumbs
      .cmp-breadcrumbs
      .cmp-heading > h1.title-xxxlarge
    .col-12                  ← stepper header
      .steppers > .steppers-header > ul > li[.active|.confirmed]
      .steppers-index (X/3)

  .row[x-data]               ← sidebar + form
    .col-12.col-lg-3         ← navscroll sidebar (hidden mobile)
      .cmp-navscroll
    .col-12.col-lg-8.offset-lg-1   ← form content
      .steppers-content > .it-page-sections-container
        section#report-place.it-page-section
          .cmp-card > .card.has-bkg-grey.shadow-sm.p-big.p-lg-4
            .card-header > h2.title-xxlarge
            .card-body > .cmp-input-autocomplete > .form-group
        section#report-info.it-page-section
          .cmp-card > .card.has-bkg-grey.shadow-sm.p-big
            .card-header > h2.title-xxlarge.icon-required
            .card-body
              .select-wrapper (select#inefficiency)
              .text-area-wrapper > .form-group.cmp-input (input#title)
              .cmp-text-area > .form-group (textarea#details)
              .btn-wrapper [x-data file upload]
                .upload-wrapper  ← file preview row
                input#file-upload-attachments.d-none
                button.btn.btn-primary.w-100.fw-bold  ← upload trigger
        section#report-author.it-page-section
          .cmp-card > .card.has-bkg-grey.shadow-sm
            .card-header > h2.title-xxlarge
            .card-body > .cmp-info-button-card > .card.p-3.p-lg-4
              (se user) h3.big-title + .accordion-item ...
              (else) <p class="text-muted">

      .cmp-nav-steps[x-data] > nav.steppers-nav
        a.btn.btn-sm.steppers-btn-prev          ← Indietro (href step precedente)
        button...steppers-btn-save.saveBtn      ← Salva (lg/mobile) + toast Alpine
        a.btn.btn-primary.btn-sm.steppers-btn-confirm  ← Avanti (href step successivo)

.bg-grey-card.shadow-contacts         ← footer contatti
  .cmp-contacts > .card > .card-body
    h2.title-medium-2-semi-bold
    ul.contact-list
```

## Acceptance Criteria

1. **Selettori CSS corretti**: correggere §27 in `segnalazione-parity.css` con i selettori reali del blade (tabella mismatch sopra); verificare che gli stili vengano effettivamente applicati in browser devtools.
2. **Visual parity**: allineare **tipografia, colori, spaziature, stepper, bordi input, pulsanti (Avanti/Indietro/Salva), card sezione, upload file, area contatti** usando CSS scoped `body.page-tests-segnalazione-02-dati` — **senza** caricare Bootstrap Italia runtime.
3. **Misura di miglioramento**: documentare before/after visivo (screenshot o checklist gap chiusi); obiettivo: **>81%** similarità HTML o elenco gap visivi risolti.
4. **HTML parity mirata** (se necessario): aggiungere classi semantiche mancanti al blade (`data-element`, gerarchie heading) senza rompere il flusso CMS/Livewire.
5. **Nessuna regressione** sulle altre pagine segnalazione: modifiche scoped a `body.page-tests-segnalazione-02-dati` salvo estratti riusabili documentati.
6. **Build**: `npm run build` in `laravel/Themes/Sixteen` dopo ogni modifica CSS/JS.
7. **Documentazione**: aggiornare solo file esistenti (`docs/visual-comparison/`, `css-js-parity.md` se esiste) con sintesi gap chiusi; nessun nuovo `.md` con date nel nome.

## Tasks / Subtasks

- [x] **[PRIORITÀ ALTA]** Correggere selettori CSS §27 con i selettori reali del blade (vedi tabella mismatch)
- [x] Verificare in browser devtools che le regole §27 vengano applicate dopo la correzione
- [x] Controllare parity visiva sezione per sezione: breadcrumb → heading → stepper → #report-place → #report-info → #report-author → nav-steps → footer contatti
- [x] Se gap residui: estendere §27 con nuove regole scoped (non creare classi helper globali)
- [x] Blade: interventi mirati solo se strettamente necessari per parity HTML o accessibilità (`data-element` mancanti)
- [x] `npm run build` in `laravel/Themes/Sixteen` dopo modifiche CSS
- [x] Aggiornare `segnalazione-02-dati-html-comparison.md` con metrica o elenco gap chiusi

## Dev Notes

### File hot

| Area | Path |
|------|------|
| Blocco blade | `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php` |
| CSS parity §27 | `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` (righe ~3104-3297) |
| CMS content | `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-02-dati.json` |
| HTML comparison | `laravel/Themes/Sixteen/docs/visual-comparison/structure-analysis/segnalazione-02-dati-html-comparison.md` |
| Body class applicata da | verificare come `body.page-tests-segnalazione-02-dati` viene iniettata (layout blade o middleware) |

### Regole di progetto

- **No Bootstrap Italia runtime**: niente CDN o bundle BI; usare Tailwind + Alpine + classi BI emulate via CSS.
- **Scoping obbligatorio**: tutte le regole §27 devono essere sotto `body.page-tests-segnalazione-02-dati`.
- **DRY**: riusare variabili CSS `:root` già definite in `segnalazione-parity.css` per colori e spaziature.
- **Naming ticket**: classi PHP = `Ticket` (non `Segnalazione`); URL/slug/traduzioni = "segnalazione".
- **No file .md con date nel nome**.

### Lezioni da Story 7.2 (step1 parity)

- Il CSS con selettori errati non produce effetti visibili; verificare SEMPRE con devtools computed styles.
- Estendere stili esistenti con nuovi selettori (DRY) piuttosto che duplicare regole.
- `npm run build` è obbligatorio: le modifiche CSS non si vedono senza il build.
- Il `body` class viene iniettato dal layout; controllare se il class name è esatto (`page-tests-segnalazione-02-dati` non `page-segnalazione-02-dati`).

### Data-element mancanti (da baseline HTML comparison)

Questi `data-element` sono nel reference ma mancano nel local (aggiungere al blade se necessario):
- `breadcrumb` (sulla `<ol>`) — **già presente nel blade** ✅
- `page-index` (sulla `<ul>` navscroll) — **già presente nel blade** ✅
- `contacts` (sul link "Richiedi assistenza") — **già presente nel blade** ✅
- `appointment-booking` (sul link "Prenota appuntamento") — **già presente nel blade** ✅

→ I `data-element` sono già tutti presenti nel blade attuale. Il gap HTML è principalmente CSS.

### Git context (ultimi commit rilevanti)

- `css: final parity fixes - all 7 segnalazione pages verified`
- `feat(css/js): Phase 2 complete + final screenshots for all 8 pages`
- `fix: remove incompatible method signatures from ModelContract interface`

## Dev Agent Record

### Agent Model Used

Composer (implementazione story 7-3)

### Debug Log References

- Build Vite: `app-Coadljxn.css` (hash da build attuale).

### Completion Notes List

- **Baseline / gap**: regole §27 puntavano a classi assenti (`.btn-back`, `.section-card`, `.btn-file-upload`, ecc.) → stili non applicati al DOM; selettori riallineati a Blade reale.
- **Blade**: `prev`/`next` come `<a href>` verso step test locali; stringhe contatti e pulsanti da `fixcity::segnalazione.*`; alert salvataggio con Alpine (`showSaveAlert`) senza handler globale inesistente.
- **Test**: Pest unit su file Blade (contratto: niente `confirmAndProceed()`, presenza chiavi traduzione e classi stepper).
- **Metrica HTML**: tabella in `css-js-parity.md` già a **97.0%** per 02-dati; story chiude gap **visivo/CSS applicabile** e i18n hardcoded.

### File List

- `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php`
- `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css`
- `laravel/Themes/Sixteen/public/manifest.json` (e `public/assets/*` da Vite)
- `laravel/public/themes/Sixteen/manifest.json` e `laravel/public/themes/Sixteen/assets/*` (sync post-build)
- `laravel/Themes/Sixteen/docs/css-js-parity.md`
- `laravel/Themes/Sixteen/docs/visual-comparison/structure-analysis/segnalazione-02-dati-html-comparison.md`
- `laravel/tests/Unit/Themes/Sixteen/Segnalazione02DatiBladeContractTest.php`

### Change Log

- 2026-04-09: Implementazione story 7-3 — §27 CSS + Blade wizard/contatti + test Pest + build Vite.

## Project context reference

- `laravel/Themes/Sixteen/docs/00-index.md`
- `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css`
- `laravel/Themes/Sixteen/docs/visual-comparison/structure-analysis/segnalazione-02-dati-html-comparison.md`

## Story completion status

Implementazione completata; stato **review** (workflow `code-review` consigliato con LLM diverso).

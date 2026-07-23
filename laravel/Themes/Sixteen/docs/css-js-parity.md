# CSS/JS Parity Phase - Design Comuni

## Obiettivo
Lavorando SOLO su CSS/JS (HTML è sacro, 99.8%+ parity strutturale), rendere il sito
visivamente identico al reference: https://italia.github.io/design-comuni-pagine-statiche/sito/

### Body minimale vs scope visivo
Per il **diff HTML** sul tag `<body>`, le rotte `/tests/*` non devono affidarsi a `class="page-tests-{slug}"` sul body (vedi [html-parity-body-policy.md](html-parity-body-policy.md)). Gli override visivi vanno su wrapper/`data-tests-slug` e selettori derivati.

## Stato Attuale (2026-04-09)

### ✅ segnalazione-01-privacy — COMPLETE
- **HTML Parity**: 99.8% (updated from 80.6%)
- **Visual Parity**: ~95%
- **Font**: Titillium Web 16px/24px #191919 ✅
- **Contact Card**: 592px wide ✅
- **Full Report**: [segnalazione-01-privacy-css-js-parity.md](segnalazione-01-privacy-css-js-parity.md)
- **Screenshots**: `screenshots/css-js-phase/segnalazione-01-privacy-*-FINAL.png`

### ⚠️ Font Parity Lesson (CRITICAL)
When fixing fonts on Design Comuni pages, ALWAYS check for conflicting rules with higher specificity:
- `body.page-tests-{slug} .text-paragraph` overrides `.segnalazione-privacy-page .text-paragraph`
- Must use same or higher specificity selector
- Reference font: Titillium Web, 16px, line-height 24px, color #191919, margin-bottom 16px

### Struttura CSS
| File | Righe | Scopo |
|---|---|---|
| `style-apply.css` | 3259 | Bootstrap Italia → Tailwind @apply mapping |
| `components/bootstrap-italia-classes.css` | 1500 | Classi Bootstrap Italia replicate |
| `ticket-parity.css` | **2517** | Stili parity flusso ticket (+850 righe) |
| `homepage-parity-v2.css` | 847 | Fix homepage parity (merge conflicts risolti) |
| Altri file parity | ~3000 | Argomenti, servizi, admin, ecc. |
| **Totale** | **~11000+** | |

**Debugbar (2026):** non usare `display:none !important` globale su `#phpdebugbar` nel CSS tema — copriva il dev anche su `/it/segnalazione-crea`. Per screenshot/visual parity nascondere con `document.body.classList.add('parity-capture-hide-dev-overlays')` (regola scoped in [`ticket-parity.css`](../resources/css/ticket-parity.css) vicino alla §26).

### Classi Mancanti Aggiunte (2026-04-09)
- `.ps-5` — padding-start 3rem
- `.has-megamenu` — navbar mega menu positioning
- `.mobile-fill` — mobile full-width utility
- `.perfect-scrollbar` — custom scrollbar styling

### Merge Conflict Risolti
- `homepage-parity-v2.css` — rimosso 2x marker `<<<<<<< HEAD` e `>>>>>>> 36abb5a44`

### HTML Parity Scores (Aggiornati 2026-04-09)
| Pagina | Match % | Struttura Ref | Struttura Locale | Status |
|---|---|---|---|--------|
| segnalazione-01-privacy | **99.5%** | 430 righe | 430 righe | ✅ COMPLETE |
| segnalazione-02-dati | **97.0%** | 559 righe | 573 righe | ✅ PASS |
| segnalazione-area-personale | **91.9%** | 886 righe | 875 righe | ✅ PASS |
| segnalazione-dettaglio | **86.8%** | 804 righe | 812 righe | ✅ PASS |
| segnalazione-03-riepilogo | **83.3%** | 523 righe | 529 righe | ✅ PASS |
| ticket-list | **72.5%** | 775 righe | 783 righe | ⚠️ NEEDS WORK |
| segnalazione-04-conferma | **43.6%** | 551 righe | 224 righe | ❌ NEEDS WORK |

**5/7 pages PASS (>80% threshold)**

### Visual Issues Found (CSS/JS Phase)
- **segnalazione-01-privacy**: ✅ FIXED - stepper shadow box, green active tab, hidden counter, brand no underline, flat contacts card
- **segnalazione-02-dati**: ✅ Story [7-3](../../../../_bmad-output/implementation-artifacts/7-3-segnalazione-02-dati-html-visual-parity.md) — §27 CSS allineata ai selettori reali del Blade (`steppers-btn-*`, `.cmp-card`, `.upload-wrapper`); testo contatti e pulsanti wizard da `fixcity::segnalazione.*`; navigazione passi come link (`prev`/`next`); alert “salva” con Alpine locale (nessun `confirmAndProceed()` fantasma).
- **segnalazione-03-riepilogo**: Phone shows literal `:phone` instead of actual number (data issue)
- **segnalazione-04-conferma**: Stepper shows circles instead of tabs; missing wrapper structure
- **segnalazione-dettaglio**: Empty green line gaps between content sections
- **ticket-list**: Title uppercase "ELENCO SEGNALAZIONI" vs reference "Elenco segnalazioni"
- **segnalazione-area-personale**: ✅ Looks good visually

### CSS Fixes Applied (2026-04-09)

#### segnalazione-01-privacy
- Stepper: shadow box, green active tab, hidden "1/3" counter, inactive tabs grey
- Header: brand link no underline
- Contacts card: flat, no shadow, 592px wide
- Font: Titillium Web 16px/24px #191919

#### segnalazione-02-dati
- Form inputs: underline-only style (border-bottom only)
- Font: 16px Titillium Web #191919
- Stepper: shadow box styling
- Contacts: green links
- §27 (2026-04-09, story 7-3): regole applicate a classi presenti nel markup; label form scoped su `.cmp-card` / `.cmp-text-area`; upload su `.btn-wrapper .btn-primary`; righe file su `.upload-wrapper`; autore su `#report-author .cmp-info-button-card`

## Architettura CSS

### Layer Order (app.css)
```
1. Tailwind base/components/utilities
2. style-apply.css (Bootstrap Italia mapping)
3. container-override.css
4. footer-override.css
5. bootstrap-italia.css
6. components/bootstrap-italia-classes.css
7. components/civic-design-components.css
8. civic-design-visual-fix.css
9. civic-design-global.css
10. ticket-parity, topics-parity, services-parity-fix, administration-parity-fix
11. app.css overrides finali
```

### Design Tokens
```css
--italia-green: #007a52
--italia-green-dark: #005c40
--italia-blue: #0073e6
--italia-gray-900: #1A1A1A
--italia-gray-700: #5C6F82
```

## Build Process
```bash
cd laravel/Themes/Sixteen
npm run build    # Compila CSS/JS
npm run copy     # Copia in public_html/themes/Sixteen/
```

## JavaScript Components
- **Alpine.js** — interattività (dropdown, search modal, rating, menu)
- **Splide** — carousel eventi
- **Nessun Bootstrap Italia JS** — tutto replicato con Alpine.js

## Checklist Verifica Manuale
Senza screenshot automatici, verificare visivamente:

### Segnalazione-01-Privacy
- [ ] Header colori (slim: #00402B, center: #007a52, nav: #007a52)
- [ ] Breadcrumb spacing e colori
- [ ] Title font-size 2.5rem, weight 700
- [ ] Stepper: step attivi (verde), inattivi (grigio)
- [ ] Privacy text: font-size 1rem, line-height 1.5
- [ ] Checkbox: dimensione 1.25rem, colore check #007a52
- [ ] Bottone "Avanti": bg #007a52, text white, border-radius 4px
- [ ] Sezione contatti: bg #f5f6f7, card con shadow
- [ ] Footer: bg #202a2e

### Segnalazione-02-Dati
- [ ] Form inputs: border #5c6f82, focus ring #007a52
- [ ] Select dropdown: wrapper con icona freccia
- [ ] Textarea: rows 5, resize none

### Segnalazione-03-Riepilogo
- [ ] Callout warning: bg giallo, icona corno
- [ ] Info summary: card con border-light, padding 1.5rem

### Segnalazione-04-Conferma
- [ ] Icona successo: verde grande
- [ ] Testo conferma: font-size 1.25rem
- [ ] Link area riservata: verde sottolineato

## Regole
- HTML NON si tocca (solo CSS/JS)
- Date nei file .md: MAI nel nome file
- Documentare ogni cambio nei docs del tema
- Build + copy dopo ogni modifica CSS

## See Also
- [Segnalazione CSS Diff](segnalazione-css-diff.md)
- [Design Comuni Master Plan](design-comuni/MASTER_IMPLEMENTATION_PLAN.md)
- [Sixteen Theme Index](../../docs/themes/index.md)
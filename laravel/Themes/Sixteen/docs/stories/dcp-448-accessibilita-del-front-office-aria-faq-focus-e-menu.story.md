---
id: dcp-448
title: "DCP-448: accessibilità del front office (menu #nav4, FAQ aria-expanded, focus delle stelle, aria-label tedesco)"
type: story
status: done
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, a11y, wcag, aria, faq, focus, header, de]
qmd: "accessibilita front office nav4 aria-hidden-focus faq aria-expanded collapsed stelle focus 2,14:1 view_all_aria WCAG 4.1.2 1.4.11 2.5.3"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./dcp-452-residui-di-ux-accessibilita-e-messa-in-produzione-del-tema.story.md
  - ./dcp-447-tema-ricostruibile-e-guardie-contro-i-mirror.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/100"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94"]
---

# DCP-448: accessibilità del front office

Status: **fatta** (2026-11-12) per i quattro difetti sotto; i residui di accessibilità non ancora lavorati stanno in DCP-452. Fasi BMAD: Analysis (audit F, UX-01, UX-07, UX-12; sweep axe),
Solution, Verification con **guardia e prova inversa** per ognuno.

## Perché esiste

Lo sweep dell'11 novembre (axe-core WCAG 2.0/2.1/2.2 AA, Playwright a 1440 e 390 px) ha trovato quattro difetti che toccano chi usa tastiera o lettore di schermo.
Un difetto del riferimento Design Comuni copiato **non diventa un contratto**: prima di toccare un template che dice «replicato tale e quale per la parity» si cerca se un test lo impone.

| ID | Difetto | WCAG | Causa verificata | Correzione |
|---|---|---|---|---|
| L-07 | `#nav4` (menu) con `aria-hidden="true"` anche su desktop: 6 link focusabili dentro un nodo nascosto, axe `aria-hidden-focus` su 14 pagine | 4.1.2 | `resources/js/theme/header-mobile-nav.js` lo impostava a prescindere dal viewport; sopra 992 px il pannello è la barra sempre visibile | `syncPanelAria()`: `aria-hidden` solo sul drawer mobile e riallineato al cambio di viewport |
| L-13 | FAQ, pratiche della segnalazione e trascrizione video: `aria-expanded="true"` su pulsanti `collapsed` con pannello chiuso (12 su 12 nelle FAQ); 12 icone con `href="…sprites.svg#"` vuoto | 4.1.2 | `content-page/sections/accordion.blade.php:24` (la nota in testa lo prescriveva «per la parity»), `report-area/section-practices.blade.php:44`, `event-detail/design-comuni.blade.php:154`; nessun test imponeva il `true` | `aria-expanded="false"` nei tre template, icona `it-expand` (presente nello sprite), nota in testa riscritta |
| L-14 | Focus da tastiera sulle stelle di valutazione: anello arancione 2 px = 2,14:1 su bianco (minimo 3:1) | 1.4.11 | il radio è 1×1 px, l'indicatore è il `box-shadow` della label, scritto da `design-comuni/components.css` (copia minificata del riferimento) con `!important` | regola in `resources/css/app/20-dc-components.css` con `!important` e specificità maggiore: anello bianco + `#1a1a1a` (da `rgb(255,153,0) 2px` a `rgb(255,255,255) 2px, rgb(26,26,26) 5px`) |
| L-10 | Home tedesca: `aria-label` «Alle Neuigkeiten anzeigen» su un link che mostra «Alle anzeigen» | 2.5.3 (comando vocale) | `view_all_aria` non conteneva `view_all_label` | «Alle anzeigen: Neuigkeiten» / «Alle anzeigen: Veranstaltungen» nei JSON di tenant e modulo |

## Guardie

| Difetto | Guardia | Prova inversa |
|---|---|---|
| L-07 | `tests/browser/header-nav-aria-hidden.spec.mjs` (3 test: 1440 assente; 390 `true`/`false`/`true`; resize 390→1440→390) | con il manifest vecchio rossa |
| L-13 | `tests/Unit/CollapsedButtonsStartCollapsedTest.php` (nessun pulsante `collapsed` con `true` nelle viste) e `tests/browser/faq-accordion-aria-expanded.spec.mjs` (chiuso `false`, clic `true` + `.show`, secondo clic `false`) | rosse con il `true` reintrodotto (12 pulsanti sbagliati) |
| L-14 | seconda prova della stessa spec di L-13/L-07 sul focus | rossa col manifest precedente |
| L-10 | `contentParityAriaMissesVisibleLabel` in `Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php` (controlla che le etichette siano più di 7, per non passare a vuoto) | rossa col testo vecchio |

I file di guardia nel tema (`CollapsedButtonsStartCollapsedTest.php` e le specifiche `header-nav-aria-hidden`, `faq-accordion-aria-expanded`) esistono sul disco (controllati il 2026-11-12).

## Verifica

Dall'audit, 2026-11-11: Playwright `header-nav-aria-hidden.spec.mjs` 3/3 + `faq-accordion-aria-expanded.spec.mjs` 2/2; con il difetto reintrodotto rosse; `npm run build` verde. Il numero 2,14:1 è il risultato di una misura, non una stima.
Oggi non ho rilanciato le spec.

## Limiti e cosa non c'è qui

- Non verificato: tecnologie assistive reali, Windows High Contrast reale, axe sulle pagine 403/419 (L-12), lingue `en/de/es` oltre ai testi toccati (l'audit axe è fatto in `it`).
- I residui (landmark senza nome, `heading-order` del footer, `aria-label="breadcrumb"` in inglese, target sotto 24 px, `forced-colors`) sono in **DCP-452**.
- Il refuso `title-snall-semi-bold` è la classe del riferimento Design Comuni: non rinominare senza controllare il CSS.

## Criteri di accettazione (verificati l'11 novembre)

- [x] 0 `aria-hidden-focus` su desktop dopo L-07.
- [x] FAQ: stato `aria-expanded` coerente con il pannello al caricamento e a ogni clic.
- [x] Anello di focus delle stelle ≥ 3:1.
- [x] `aria-label` che contiene l'etichetta visibile nelle quattro lingue della home.
- [ ] Axe rilanciato dopo tutte le correzioni a macchina libera (non fatto in questo giro).

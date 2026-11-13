---
title: "Audit di accessibilita' (DCP-143): axe, tastiera e cio' che manca"
type: concept
tags: [accessibility, wcag, axe, aria, keyboard, playwright, bmad]
created: 2026-10-13
updated: 2026-10-13
story: dcp-143
related:
  - ../../bmad/stories/dcp-143-accessibilita-difetti-aria-e-audit-manuale-wcag-21-aa.story.md
  - ../../../../../../bashscripts/docs/visual-comparison/keyboard-audit.md
  - ../../../../../../bashscripts/docs/visual-comparison/a11y-page-probe.md
qmd: "accessibilita axe wcag aria tablist role=tab progressbar aria-label focus tastiera escape keyboard-audit a11y-axe.spec playwright nvda non verificato dcp-143"
status: draft
---

# Accessibilita': come si misura e che cosa e' stato corretto

## Strumenti (tutti in questo repository)
- `laravel/Themes/Sixteen/tests/browser/a11y-axe.spec.mjs`: Playwright + axe-core (wcag2a/aa, wcag21a/aa), 15 pagine a 375 e 1440 px, wizard (3 passi) e area personale con una sessione di prova (`FIXCITY_STORAGE_STATE`). Fallisce su serious/critical.
- `laravel/Themes/Sixteen/tests/browser/header-menus-keyboard.spec.mjs`: Esc chiude i menu dell'intestazione e il focus torna al pulsante.
- `bashscripts/tools/visual/keyboard-audit.mjs`: Tab in sequenza, nome accessibile, focus visibile, ordine.
- `laravel/Modules/Fixcity/tests/Feature/Pages/ThemeAriaStructureTest.php`: struttura ARIA nell'HTML (tablist, aria-controls, progressbar) senza browser.
- Non c'e' un job CI che li esegua: servono un'app e un account di prova.

## Regole che ne sono uscite
- Un `ul[role=tablist]` contiene `li[role=presentation] > a[role=tab]` con `aria-controls` su un `role=tabpanel` che esiste. Il riferimento Design Comuni ha `li[role=tab] > a[role=button]` e `aria-controls` su un id inesistente: axe lo segnala (critical). Elenco di segnalazioni (`ticket-list`) e `report-list` seguono la forma valida. Se le schede sono link ad altre pagine (`tests-index`), niente `role=tablist`.
- Ogni `role=progressbar` ha un nome: `aria-label="{{ __('pub_theme::a11y.reading_progress') }}"` (4 lingue in `Themes/Sixteen/lang/*/a11y.php`). Il costo sulla parity HTML e' misurato (circa -0,2 punti sulle pagine che lo mostrano); il blocco `report-partials/navscroll` di un'altra sessione ha la scelta opposta (etichetta solo se la pagina la passa).
- Nel menu a tendina dell'intestazione, `closeDropdownMenu` (`resources/js/app.js`) rimette il focus sul pulsante se era dentro il menu: prima Esc mandava il focus sul body.
- Il namespace delle lang del tema e' `pub_theme::`, non `sixteen::` (quest'ultimo non risolve: `__('sixteen::navigation.main')` torna la chiave).

## Limiti dichiarati
Nessun lettore di schermo (NVDA, VoiceOver, TalkBack) e' stato usato: NON VERIFICATO. La tastiera e' pilotata da uno script su Chromium. L'iframe di YouTube nelle pagine di prova ha violazioni sue (terza parte).

# segnalazione-crea Header/Stepper Responsive - Fix Plan

**Status**: Active  
**Created**: 2026-04-12  
**Last Updated**: 2026-05-04  
**Category**: Header / Stepper / Design Comuni  
**Related Story**: [7-29](../../../_bmad-output/implementation-artifacts/7-29-segnalazione-crea-header-stepper-responsive-multilingual.md)

## Changelog 2026-05-04

- **Id duplicato**: rimosso `id="main-container"` dal wrapper del wizard in `filament/widgets/create-ticket-wizard.blade.php` (resta solo su `<main>`). Evita HTML invalido e fa funzionare i selettori `main#main-container` / `main #main-container` del blocco stepper in `app.css`.
- **Traduzioni Fixcity**: corretta struttura array in `Modules/Fixcity/lang/it/segnalazione.php` (chiavi `privacy` duplicate/orfane rimosse; `error.not_accepted` dentro `privacy`).
- **Stepper markup statico**: in `design-comuni-global-fixes.css` gli stati completati coprono sia `li.confirmed` sia `li.completed`.

## Issues Identified

### 1. Stepper non responsive
Lo stepper di `segnalazione-crea` non scala correttamente su mobile/tablet.
**Fix**: Estendere selettori CSS mobile-first da story 7.28 per coprire segnalazione-crea.

### 2. Hamburger allineato in alto
`custom-navbar-toggler` non e centrato verticalmente nel navbar.
**Fix**: `align-self: center` su `.custom-navbar-toggler`.

### 3. Search label nascosta
`.search-label` presente in HTML ma non visibile.
**Fix**: Verificare display/color/visibility CSS.

### 4. Language selector non funziona
`data-bs-toggle="dropdown"` richiede Bootstrap JS.
**Fix**: Implementare con Alpine.js se JS non disponibile.

### 5. Icona lingua stile diverso
Icona expand ha sfondo/colore diverso dal reference.
**Fix**: Allineare CSS al reference.

## Header Files

- `laravel/Themes/Sixteen/resources/views/components/bootstrap-italia/header.blade.php` — header condiviso
- `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` — CSS parity
- `laravel/Themes/Sixteen/resources/css/design-comuni-global.css` — CSS globali

## Related Documentation

- [Ticket wizard frontoffice](../../../Modules/Fixcity/docs/ticket-wizard-frontoffice.md) — wizard Filament, `main-container`, testo privacy
- [BODY_CLASS_RULE.md](BODY_CLASS_RULE.md) — HTML parity: `<body>` senza classi
- [STEPPER_MOBILE_FIRST_RULE.md](STEPPER_MOBILE_FIRST_RULE.md) — Stepper mobile-first CSS
- [Story 7-28](../../../_bmad-output/implementation-artifacts/7-28-segnalazione-02-dati-stepper-responsive-multilingual.md) — Stepper responsive + i18n
- [Story 7-29](../../../_bmad-output/implementation-artifacts/7-29-segnalazione-crea-header-stepper-responsive-multilingual.md) — Header/stepper crea fix

## Notes

L'header e condiviso tra TUTTE le pagine tests. Qualsiasi modifica impattera multiple pagine. Testare sempre tutte le pagine dopo le modifiche.

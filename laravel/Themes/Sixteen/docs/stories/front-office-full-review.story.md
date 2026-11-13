---
title: "Sixteen - Controllo completo del front office (anonimo e cittadino)"
type: story
bmad: true
status: done
created: 2026-11-12
owner: Themes/Sixteen
related:
<<<<<<< .merge_file_sAQpo1
  - ../../../../Modules/Fixcity/docs/stories/release-readiness-ci-and-flows.story.md
=======
  - https://github.com/laraxot/module_fixcity_fila5/blob/dev/docs/stories/release-readiness-ci-and-flows.story.md
>>>>>>> .merge_file_0oApaA
  - https://github.com/laraxot/theme_sixteen_fila5/issues/116
  - https://github.com/laraxot/theme_sixteen_fila5/issues/117
  - https://github.com/laraxot/module_fixcity_fila5/issues/70
  - https://github.com/laraxot/module_fixcity_fila5/issues/73
---

# Controllo completo del front office

App avviata da zero (PHP 8.4, MariaDB, dati demo) e percorsa con Playwright, desktop 1366 px e mobile 390 px, da anonimo e da
cittadino registrato: registrazione, verifica email, wizard con foto, Traccia, dettaglio, area personale.

## Come lavorare sul CSS del tema (da ricordare)

`postcss/isolate-legacy-important.cjs` limita **ogni** regola fuori ambito agli elementi che NON stanno in una pagina
`data-dc` (`:not(:where(body:has(.page-content[data-dc]) *))`). Quasi tutte le pagine reali sono `data-dc`, quindi una regola
nuova senza prefisso non si applica. Una regola per le pagine reali va scritta con il prefisso
`body:has(.page-content[data-dc]) ...`. In alternativa deve rientrare nelle esenzioni: `.it-header-*`, `.it-footer-*` o
`.it-brand-*` con `!important`, `.cmp-wizard-widget`, `.fc-*`, mappa, editor. Va verificata nel browser sulla pagina vera, non
su una pagina di prova senza `data-dc`.

## Corretto

| Difetto | Correzione |
| --- | --- |
| Campo «Dettagli» nero con testo scuro su dispositivo in tema scuro (#116) | `data-theme="light"` su `<html>` di tutti i layout (daisyUI non applica piu' `color-scheme: dark`), `color-scheme: light`, sfondo bianco dei campi del wizard |
| Traccia senza elenco delle segnalazioni (#117) | «Le tue segnalazioni» per l'utente autenticato (`components/pages/ticket-track.blade.php`), invito ad accedere per l'anonimo; CSS in `app/21-ticket-track.css` |
| Indicatore di caricamento di accesso e registrazione largo ~470 px | Dimensioni fisse a 20 px (SVG proprio e `fi-loading-indicator`), con prefisso `data-dc` |
| Testata con l'email dell'utente | Nome, poi nome e cognome, poi email |
| Voce di menu attiva | Vince il percorso piu' lungo; `header_active` `track` sulla pagina Traccia |
| Piede alto 775 px e copyright su bianco | `min-height: 0`, riga del copyright nella fascia scura |
| Build impossibile senza rete | Tolto il provider `google()` di laravel-vite-plugin (font gia' self-hosted) e `mauve` (inutilizzato) |

## Aperto

- Peso del CSS (3,3 MB su ogni pagina): #102.
- Gravatar e tile caricati prima del consenso: #106.

---
title: "Passata UX mobile del tema Sixteen (viewport, prototipo mobile, mappa a schermo intero)"
type: story
theme: Sixteen
status: review
created: 2026-10-28
updated: 2026-10-29
tags: [docs, sixteen, mobile, accessibility, bmad]
---

# Passata UX mobile — tema Sixteen

> **Correzione del 2026-10-29: la premessa «tema da migliorare = Sixteen» era sbagliata.** Il tema servito dal tenant `local/techplanner` e' **Two** (vedi «Tema effettivo» sotto); la passata mobile giusta e' [quella di Two](../../../Two/docs/stories/mobile-ux-pass.story.md). Sixteen e' il clone Design Comuni del progetto Fixcity e deve restare 1:1 col riferimento (regola [013](../../../../../docs/wiki/rules/013-design-comuni-html-match.md)): qui si correggono solo difetti di parity verificati sul riferimento, non si fanno passate di UX generiche. Di conseguenza:
>
> - **Difetto 5 (`loading="lazy" decoding="async"`): annullato.** E' markup che il riferimento non ha; tolto da `components/blocks/segnalazioni/layout.blade.php:288`.
> - **Difetti 1-3** riguardano le viste `mobile/**`, un prototipo non instradato e non Design Comuni: non toccano la parity, ma restano da riesaminare (instradare o rimuovere).
> - **Difetto 4 (`100dvh` nei CSS):** resta nel codice, ma va confermato contro il riferimento (stile calcolato e screenshot) prima di considerarlo accettato; nessuna build fatta.
> - **Header e menu mobile:** gli attributi `data-sixteen-*` erano sbagliati, vedi [data-attribute-rule.md](../data-attribute-rule.md) e [data-attribute-parity.story.md](./data-attribute-parity.story.md). Lo stato `done` qui sopra e' diventato `review` per questo.

**Fase BMAD**: Dev (diff minimo, ponytail). **Richiesta utente**: «studia e migliora la ui/ux da mobile fa tutto con bmad + ecc + second brain + filament blueprint + ponytail».

## Contesto e perimetro

Sixteen ha gia' molto lavoro mobile (`bottom-nav` con `safe-area-inset-bottom` e `prefers-reduced-motion`, `14-auth-login.css` con bottoni 44 px, `layouts` con `theme-color`). Questa passata si limita ai difetti residui trovati leggendo layout, viste `mobile/**` e CSS.

**Tema effettivo.** Il tenant `local/techplanner` ha in `laravel/config/local/techplanner/xra.php` righe 10-12 tre chiavi `'pub_theme'` (`Two`, `Sixteen`, `Two`): in un array PHP vince l'ultima, quindi il tema servito e' **Two** ([passata Two](../../../Two/docs/stories/mobile-ux-pass.story.md)). Le correzioni di Sixteen valgono quando il tenant usa Sixteen. Il file `xra.php` non e' stato toccato: e' configurazione del tenant e va ripulito dall'utente.

**Non toccato di proposito.** `components/sections/header/v1.blade.php`, `resources/css/mobile-header-fix.css`, `resources/js/theme/header-mobile-nav.js` e `docs/wiki/index.md` hanno modifiche in corso di un'altra sessione (story `dcp-121`, [contratto di navigazione mobile](../wiki/concepts/mobile-navigation-contract.md)).

## Difetti e correzioni (file:riga prima della modifica)

| # | Principio | Difetto (file:riga) | Correzione |
|---|---|---|---|
| 1 | Mai bloccare lo zoom (WCAG 1.4.4) | `resources/views/mobile/layout.blade.php:5`: `maximum-scale=1.0, user-scalable=no`. Era l'unico `user-scalable` del tema. | Viewport `width=device-width, initial-scale=1.0` + `theme-color #007a52`; il salto di zoom di iOS si evita con input a 16 px. `min-h-screen` -> `min-h-dvh`. |
| 2 | Campi a 16 px e label associate | `mobile/tickets/create.blade.php:13,18,43,50`: `text-sm` (14 px) sui campi, label senza `for`/`id`, `py-2` (circa 38 px). | `text-base`, `for`/`id`, `min-h-11`, `py-3`, `autocomplete="off" enterkeyhint="next"` sul titolo; invio `min-h-12`. |
| 3 | Bersaglio 44 px | `mobile/tickets/create.blade.php:35,44` ("Apri fotocamera", "Rileva", testo `text-sm` senza padding); link "Home"/"Torna" in `mobile/profile.blade.php:4`, `mobile/tickets/create.blade.php:4`, `mobile/tickets/show.blade.php:4`. | `min-h-11` (+ `min-w-11`/`px`), `inline-flex items-center` sui link. |
| 4 | Altezza dinamica (`dvh`) | `resources/css/mobile-map-fix.css:144,152,153` e `resources/css/app/10-header-navigation-contracts.css:60,62,70`: mappa a schermo intero (`fixed`, usata nel wizard di segnalazione per scegliere il punto) con `100vh`: su iOS/Android supera l'area visibile e il fondo mappa e i controlli finiscono sotto la barra URL. | Dichiarazione `100dvh !important` aggiunta dopo ogni `100vh` (fallback per i browser senza `dvh`). |
| 5 | ~~Immagini senza caricamento differito~~ **ANNULLATO (2026-10-29)** | `components/blocks/segnalazioni/layout.blade.php:288`: foto della segnalazione a larghezza piena senza `loading`. | ~~`loading="lazy" decoding="async"`~~ tolto: il riferimento non ha quegli attributi, quindi erano drift dalla parity. |

## Cosa NON e' stato fatto e perche'

- **`pub_theme` triplicato** in `config/local/techplanner/xra.php`: configurazione del tenant, decide l'utente.
- **Ricompilazione asset** (`npm run build` in `Themes/Sixteen`): i sorgenti CSS sono modificati ma la pagina servita cambia solo dopo la build; decisione dell'utente. Nessuna dipendenza aggiunta.
- **Viste `mobile/**` dormienti.** `<x-mobile::layout>` non risulta registrato come namespace (`ThemeServiceProvider` registra `sixteen`/`pub_theme`) e non ho trovato rotte `/mobile`: sembrano un prototipo non instradato. Sono state corrette per non lasciare un `user-scalable=no` pronto a essere riattivato, ma restano da decidere (instradarle o rimuoverle). In piu' `mobile/home.blade.php:25` e `mobile/tickets/index.blade.php:10` usano `<template x-data x-init>` con un `<template x-for>` annidato dentro: il contenuto di un `<template>` non e' nel DOM figlio e Alpine, con ogni probabilita', non lo inizializza, quindi l'elenco non si popolerebbe (non provato nel browser); non corretto perche' richiede di decidere se il prototipo vive.
- **`map-visual-fix.css:156-164`**: stesso `100vh`, ma il file non e' importato da `app.css` (codice morto), non modificato.
- **Login/registrazione**: il form e' reso dal widget Filament nel modulo User dentro blocchi CMS; il CSS `14-auth-login.css` copre gia' i 44 px. `autocomplete`/`inputmode` dei campi: ambito del modulo User.
- Altri `100vh` in `style-apply.css:766,3514`, `search-results-parity.css:78`, `bootstrap-italia.css`: contenitori con `calc(... - px)` o `max-h`, non verificati su dispositivo; fuori da questa passata.
- Header/menu mobile: vedi sopra, lavoro in corso di un'altra sessione.

## Verifica manuale suggerita (360 / 390 / 430 px)

1. Ticket wizard, passo posizione: aprire la mappa a schermo intero su Safari iOS a 390 px: nessun fondo tagliato, pulsante di chiusura raggiungibile; ruotare in landscape e ripetere.
2. ~~Dettaglio segnalazione con 2+ foto: le foto fuori schermo non vengono scaricate finche' non scorrono.~~ Annullato con il difetto 5.
3. Se si decide di instradare le viste `mobile/**`: pizzicare per ingrandire (deve funzionare), toccare i campi di `create` (nessun salto di zoom iOS), tutti i bottoni >= 44 px.

## Evidenza di verifica (statica, nessun server avviato)

- `git diff` sui soli file elencati; Blade riletti e controllati per bilanciamento di `@if`, `@php` e `{{ }}`.
- Nessun Pest/migrate/build eseguito (dati sacri).

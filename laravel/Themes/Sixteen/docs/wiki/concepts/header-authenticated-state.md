---
title: "Header slim — stato autenticazione (Design Comuni)"
type: concept
confidence: high
updated: 2026-06-05
tags: [header, authentication, area-personale, bootstrap-italia, six, parity, r21]
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/277
related:
  - header-logged-in-parity-delta.md
  - ../../../../../../docs/wiki/rules/design-comuni-header-parity.md
  - ../../../../../../docs/wiki/memories/header-html-visual-parity-rule.md
  - ../playwright-visual-testing.md
sources:
  - ../../../resources/views/components/sections/header/v1.blade.php
  - ../../../lang/it/ui.php
  - ../../../Main_files/five/segnalazione-area-personale.html
  - https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
---

# Header slim — stato autenticazione (Design Comuni)

## Parity doppia (2026-06-05)

HTML (`cmp-header.hbs`) + visual (screenshot / computed CSS) vs [DC area personale](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html). Toggle logged = stesso pattern guest. Dettaglio: [header-logged-in-parity-delta.md](header-logged-in-parity-delta.md).

## Regola operativa (memoria progetto)

| Stato | Cosa deve comparire nella `it-header-slim-right-zone` (zona destra della barra slim) |
|--------|----------------------------------------------------------------------------------------|
| **Non connesso** | Un solo pulsante primario: `pub_theme::header.guest.cta.label`, link `route('login')` (Folio auth). |
| **Connesso** | Blocco utente slim: **nome leggibile protagonista**, **avatar secondario**, **dropdown** con voci `pub_theme::header.user.dropdown.*.label` e link `route('services.categories')`, `route('dashboard')`, `route('notifications')`, `route('profile.edit')`, **Esci** via `route('logout')` (pagina Folio GET). |

Non mostrare il pulsante «Accedi all'area personale» a utente già autenticato.

## Implementazione (path reale prima di tutto)

Per questo flusso il path reale parte da:

- `<x-section slug="header" />`

e va verificato prima in:

- `resources/views/components/sections/header/v1.blade.php`

Solo dopo si controllano eventuali partial secondari o file delegati.

Regola pratica:

- mai assumere direttamente `bootstrap-italia/header.blade.php` come owner senza verificare il percorso reale della section.
- eventuali estrazioni DRY + KISS locali all'header devono restare sotto `resources/views/components/sections/header/partials/`, mantenendo `v1.blade.php` come entrypoint e owner del wiring.

Implementazione corrente consolidata per `segnalazione-crea`:

- il file owner reale è `resources/views/components/sections/header/v1.blade.php`
- i dropdown slim usano `data-bs-toggle="dropdown"` e vengono gestiti dal runtime owner-side in `resources/js/app.js`
- il resolver utente del section header privilegia il nome visibile e usa l'avatar solo come segnale secondario

Logica: `@guest` / `@else`. Dropdown slim lingua + utente autenticato: **solo** `data-bs-toggle="dropdown"` + `resources/js/app.js` (Story **7-54**), coerente col blocco guest parity e col polyfill `DOMContentLoaded`.

Meccanismo attivo:
```blade
<div class="dropdown">
    <button data-bs-toggle="dropdown" aria-expanded="false">
    <div class="dropdown-menu">
```

**VIETATO** un secondo sistema dropdown (es. Alpine inline su `langOpen` / `userOpen`) per questi due controlli: su pagine **Livewire + Filament** (es. `tests/segnalazione-crea`) Alpine inline può non agganciarsi e i menu restano chiusi.

Traduzioni menu: namespace **`pub_theme`**, file `lang/{locale}/header.php`, chiavi **`header.user.dropdown.*.label`** (prototipo 5 livelli). Deprecato: `ui.header_area_personale` in `ui.php` — non usare in nuovi Blade.

Resolver auth corrente:

- display name: preferire nickname/profile owner-side e degradare fino a `email`
- avatar: preferire il resolver del `Profile`, non un path hardcoded nel tema

## HTML + visual parity (regola 2026-06-05)

Logged slim toggle **deve** usare `btn btn-primary btn-icon btn-full` + `rounded-icon` come `cmp-header.hbs` — non `nav-link dropdown-toggle`. Memoria: [header-html-visual-parity-rule](../../../../../../docs/wiki/memories/header-html-visual-parity-rule.md). UX: [STORY-147-ux-design](../../../../../../docs/stories/STORY-147-ux-design-header-logged-in.md).

## Riferimento visivo

- Statico ufficiale: [segnalazione area personale](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html)
- Copia locale HTML: `Main_files/five/segnalazione-area-personale.html`
- HBS: `docs/design-comuni/raw/cmp-header.hbs` righe 33–68

## Verifica con screenshot (guest vs auth)

1. Avviare l’app (`php artisan serve`).
2. Aprire ad esempio `/{locale}/tests/segnalazione-crea` (stesso header delle altre pagine che usano la section `header`).
3. **Guest**: catturare viewport con testo «Accedi…» e **nessun** nome utente.
4. Effettuare login, ricaricare la stessa URL: catturare **nome/avatar + chevron + menu** (aprire il dropdown e salvare un secondo screenshot se serve review UX).
5. Verificare anche il dropdown lingue: chiuso/aperto, coordinamento con il dropdown utente e parity colori del menu rispetto al reference.

Procedure strumentale: [playwright visual testing](../playwright-visual-testing.md) e, in root, `docs/wiki/concepts/playwright-mcp-screenshots.md` se presente.

## Link di menu (implementazione corrente)

SSoT: [fo-folio-named-routes-header.md](fo-folio-named-routes-header.md) — verificare con `php artisan folio:list`.

| Voce menu | `route()` |
|-----------|-----------|
| I miei servizi | `services.categories` |
| Le mie pratiche | `dashboard` |
| Notifiche | `notifications` |
| Impostazioni | `profile.edit` |
| Esci | `logout` |
| Accedi (guest) | `login` |

**Vietato** in header produzione: `route('user.services')`, `route('tests.view', …)`, `FrontofficeUrl::personalArea*`.

`FrontofficeUrl::fromStoredUrl()` solo per nav CMS da `header.json`.

## Contratto dropdown funzionale (regola 2026-04-20)

I dropdown lingua e utente slim del section owner usano `data-bs-toggle="dropdown"` con runtime owner-side in `app.js`. Devono essere **funzionalmente verificati**, non solo presenti nel DOM.

Checklist obbligatoria per ogni fix/story sull’header:
- [ ] click sul toggle → dropdown si apre (`.show` + `display:block` su `.dropdown-menu`)
- [ ] secondo click o click fuori → dropdown si chiude
- [ ] `app.js` caricato nel layout usato dalla pagina
- [ ] nessun Livewire `wire:navigate` o re-render che distrugge i listener owner-side
- [ ] z-index e overflow non bloccano il menu
- [ ] avatar/fallback resta secondario rispetto al nome
- [ ] nome utente resta leggibile e protagonista nello stato autenticato
- [ ] chevron resta visibile come affordance dropdown
- [ ] colori sfondo/testo/icone/hover/focus coerenti con reference `graduatoria-area-personale.html`
- [ ] screenshot prima/dopo salvati come evidenza

Cause tipiche di dropdown non funzionanti:
- `app.js` non caricato nel layout specifico
- doppio sistema di interazione tra Alpine inline e runtime owner-side
- Livewire full-page re-render che distrugge i listener vanilla JS
- z-index / overflow / stacking context bloccante

## Regola UX blocco utente slim — "Mario Rossi"

Dalla reference `graduatoria-area-personale.html`, il blocco utente slim:
- comunica autenticazione tramite **NOME leggibile** (`Mario Rossi`) — non l'avatar
- il nome è **protagonista**: ben leggibile, non compresso in pill
- avatar/icona è **secondaria**, non dominante
- freccia dropdown è **visibile** — affordance obbligatoria
- NON deve sembrare un pulsante generico verde appiattito

**Vincoli visivi:**
- `VIETATO`: dare all'avatar peso visivo dominante
- `VIETATO`: comprimere il nome in una pill illeggibile
- `OBBLIGATORIO`: nome utente protagonista, leggibile, piena visibilità

## Anti-pattern

- Duplicare questa logica in altre view dell’header senza estrarre include: mantenere **un solo** punto (`sections/header/v1.blade.php`).
- Assumere il file owner dell’header senza seguire il path reale della section.
- Stringhe italiane hardcoded nel Blade per il menu utente: usare sempre `pub_theme::header.user.dropdown.*.label`.
- Considerare i dropdown slim come decorazione invece che come parte del contratto runtime di navigazione.
- Validare i colori dei dropdown senza confronto screenshot sul reference reale.
- Chiudere una story header senza verificare click/open/close nel browser reale.

## Backlink

- [Story 5.0](../../../../../../.planning/stories/5.0-header-logged-in-state.story.md)
- [Overview tema](./../overviews/sixteen-theme.md)

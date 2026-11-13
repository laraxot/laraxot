---
title: "php artisan optimize verde e pagine Volt con proprieta' bloccate"
type: story
status: review
theme: Sixteen
created: 2026-10-28
updated: 2026-10-28
tags: [bmad, story, optimize, view-cache, blade, livewire, locked, sicurezza, log]
qmd: "artisan optimize view cache blocks::geo.mappa-static Locked pageSlug Cannot assign array CannotUpdateLockedProperty APP_DEBUG APP_ENV local viste rotte"
related:
  - ../../../../Modules/Fixcity/docs/stories/dcp-379-foto-nel-dettaglio-della-segnalazione.story.md
---

# Optimize verde e pagine Volt bloccate

## Richiesta

«esegui cd laravel && php artisan optimize e sistema tutte le segnalazioni cercando di capire sempre la fonte».

## Analisi (prove)

| Segnalazione | Fonte | Esito |
|---|---|---|
| `optimize` fallisce: `Unable to locate a class or view for component [blocks::geo.mappa-static]` | `components/blocks/tests/segnalazione-dettaglio.blade.php` usava un namespace `blocks::` mai registrato, e conteneva `['file' => ... ?? '-')` senza `]` | I tre blocchi `tests/segnalazione-dettaglio`, `tests/ticket-dettaglio`, `tests/ticket-detail` non sono usati da nessun JSON, codice o test (i JSON usano `report-detail` e il dettaglio reale e' `ticket-detail/design-comuni`). Erano copie del dettaglio che stampavano `File: <nome>` e l'URL assoluto dell'originale: rimossi |
| Log: `Cannot assign array to property ...::$pageSlug of type string` | Richiesta `/livewire/update` che riscrive una proprieta' pubblica della pagina Volt. Livewire 4: le proprieta' pubbliche sono modificabili dal client salvo `#[Locked]` | `#[Locked]` su tutte le proprieta' di contesto (slug, container, `pageSlug`, `isDesignComuni`, `data`) delle 8 pagine Folio/Volt di `pages/` |
| Log 27/10 21h: `<<`, `expecting endif` in `ticket-detail/design-comuni`, `Unable to locate file in Vite manifest` | Marker di merge committati dal sync `git add -A` (vedi DCP-379) | Gia' risolto ieri, nessuna nuova occorrenza |
| Log: `Command "filament:update" is not defined` | Comando digitato a mano una volta (07:43); quello reale e' `filament:upgrade` (gia' in `composer.json`) | Nessuna modifica |

## Debito registrato (non raggiungibile, non corretto)

Uno scanner che compila ogni vista con il compilatore Blade dell'app e passa il risultato a `php -l` trova 38 viste che non
diventano PHP valido (es. `auth/forgot-password`: righe duplicate da un vecchio merge, `<x-authentication-card>` aperto due volte).
`view:cache` non le segnala perche' compila senza fare il lint. Nessuna e' raggiungibile da route, JSON CMS o componente attivo
(verificato per riferimento); sono avanzi Jetstream, `pages_old*`, template e prove. Ponytail: niente pulizia di massa su 8 moduli
senza un effetto per l'utente. Da decidere: rimuoverle o ripararle.

- `Themes/Sixteen/resources/views/templates/home.blade.php`
- `Themes/Sixteen/resources/views/templates/landing.blade.php`
- `Themes/Sixteen/resources/views/pages_old1/administration/index.blade.php`
- `Themes/Sixteen/resources/views/pages_old1/services/index.blade.php`
- `Themes/Sixteen/resources/views/pages_old1/news/index.blade.php`
- `Themes/Sixteen/resources/views/profile/update-password-form.blade.php`
- `Themes/Sixteen/resources/views/profile/update-profile-information-form.blade.php`
- `Themes/Sixteen/resources/views/profile/logout-other-browser-sessions-form.blade.php`
- `Themes/Sixteen/resources/views/components/feedback/toast.blade.php`
- `Themes/Sixteen/resources/views/components/navigation/navbar.blade.php`
- `Themes/Sixteen/resources/views/components/header/user-dropdown.blade.php`
- `Themes/Sixteen/resources/views/components/data-display/list-group.blade.php`
- `Themes/Sixteen/resources/views/components/auth/confirms-password.blade.php`
- `Themes/Sixteen/resources/views/components/ui/header.blade.php`
- `Themes/Sixteen/resources/views/layouts/navigation.blade.php`
- `Themes/Sixteen/resources/views/layouts/layout-test.blade.php`
- `Themes/Sixteen/resources/views/api/api-token-manager.blade.php`
- `Themes/Sixteen/resources/views/livewire/appointment/create-appointment.blade.php`
- `Themes/Sixteen/resources/views/teams/delete-team-form.blade.php`
- `Themes/Sixteen/resources/views/teams/update-team-name-form.blade.php`
- `Themes/Sixteen/resources/views/teams/team-member-manager.blade.php`
- `Themes/Sixteen/resources/views/teams/create-team-form.blade.php`
- `Themes/Sixteen/resources/views/navigation-menu.blade.php`
- `Themes/Sixteen/resources/views/auth/two-factor-challenge.blade.php`
- `Themes/Sixteen/resources/views/auth/confirm-password.blade.php`
- `Themes/Sixteen/resources/views/auth/forgot-password.blade.php`
- `Themes/Sixteen/resources/views/pages_old/bootstrap-italia-showcase.blade.php`
- `Modules/Gdpr/resources/views/account/delete.blade.php`
- `Modules/Notify/resources/views/admin/index/acts/manage_template.blade.php`
- `Modules/Notify/resources/views/filament/tables/columns/contact-column.blade.php`
- `Modules/UI/resources/views/components/profile/dropdown.blade.php`
- `Modules/UI/resources/views/filament/widgets/stat-with-icon.blade.php`
- `Modules/User/resources/views/components/blocks/hero.blade.php`
- `Modules/Xot/resources/views/rss/feed.blade.php`
- `Modules/Xot/resources/views/livewire/test.blade.php`
- `Modules/Cms/resources/views/livewire/menu/partials/builder_body_a.blade.php`
- `Modules/Cms/resources/views/livewire/menu/partials/builder_body_b.blade.php`
- `Modules/Job/resources/views/livewire/schedule/crud.blade.php`

## Fuori dal codice (azione del proprietario)

`.env` di produzione: `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://fixcity.local`. Effetti misurati: la pagina di errore
pubblica mostra stack, query e il cookie `laravel_session`; i link Media Library puntano a `fixcity.local`; `InjectBoost` gira in
produzione. Valori attesi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://fixcity.eu`, poi `php artisan optimize`.

## Criteri di accettazione

- [x] `php artisan optimize` termina senza FAIL.
- [x] Una richiesta Livewire che modifica `pageSlug` viene respinta con `CannotUpdateLockedPropertyException`.
- [x] Le pagine pubbliche principali rispondono come prima.

## Verifica

- `php artisan optimize`: config, events, routes, views, blade-icons, filament DONE.
- Attacco reale: snapshot Volt di `/it/tickets` + `updates: {pageSlug: "home"}` su `/livewire-*/update` -> log `Cannot update locked property: [pageSlug]`.
- `/it`, `/it/tickets`, `/it/tickets/track`, `/it/auth/login`, `/it/auth/register`, `/it/blog`, `/it/mappa-sito`, `/it/tests/segnalazione-dettaglio`: 200.

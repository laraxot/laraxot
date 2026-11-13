---
title: "Sixteen: chiavi pub_theme bloccate dal rekey e nomi di rotta che non esistono"
type: story
status: ready-for-dev
theme: Sixteen
epic: i18n-conventions
updated: 2026-10-30
tags: [i18n, pub_theme, routes, container0, folio, contract-test, residuals]
related:
  - ../../../../Modules/Lang/docs/stories/translation-key-shape-rekey.story.md
  - ../../../Two/docs/stories/container0-routes-and-pub-theme-namespace.story.md
  - ../../../../../bashscripts/ai/wiki/memories/translation-protocol-memory-aid.md
---

# Sixteen: residui di chiavi e di rotte

**Story ID:** i18n-conventions.sixteen-residuals
**Status:** ready-for-dev   <!-- backlog -> ready-for-dev -> in-progress -> review -> done -->

> Tema Sixteen = clone Design Comuni: l'HTML `it` deve restare identico al riferimento. Questa story cambia **chiavi e href**, mai i testi ne' il markup.

## Story

Come **chi mantiene il front-office Design Comuni**, voglio che le chiavi di traduzione di Sixteen abbiano la forma namespace + 4 segmenti e che ogni `route('nome')` delle sue Blade esista, cosi' che nessuna pagina del tema lanci `RouteNotFoundException` al render e le chiavi siano leggibili dal gestore traduzioni.

## Acceptance Criteria

<!-- LOCKED. -->

1. Le chiavi `pub_theme::ui.mobile.*` (31), `accessibility.contrast`, `accessibility.high_contrast_toggle` e `footer.documents` hanno forma namespace + 4 segmenti in `it`, `en`, `de`, `es`, con gli stessi valori e **senza perdere i commenti** dei file lingua (`lang/en/ui.php`, `lang/de/footer.php`, `lang/de/accessibility.php`).
2. Ogni `route('nome')` delle Blade di Sixteen raggiunte da una pagina usa un nome che il tema dichiara (`container0.view`, `container0.index`) o uno stabile (`home`, `login`, `logout`, `register`, `password.request`); nessun `url('/...')` e nessun `href="/it/..."` per pagine del sito.
3. Il test di contratto dell'header e' allineato alla scelta fatta per `services.categories` (vedi Dev Notes, decisione A o B).
4. Il blocco `components/blocks/header-area-personale/default.blade.php` (non referenziato da nessun file) e' dichiarato morto in questa story oppure riportato su `FrontofficeRoutes`.
5. L'HTML `it` delle pagine toccate non cambia rispetto al riferimento Design Comuni (confronto prima/dopo dell'output, a parita' di dati).
6. `check-key-shape.py Themes/Sixteen` non elenca piu' le chiavi di AC #1; `check-blade-keys.php` non trova chiavi nuove mancanti.

## Tasks / Subtasks

- [ ] Estendere `lang-rekey.php` perche' sposti chiavi in un file con commenti (riscrivere solo il blocco dell'array interessato) oppure farlo a mano file per file (AC: #1)
- [ ] Rinominare le 31 `ui.mobile.*` (tipo: `title`, `description` -> `.label`; `describe_*` -> `.placeholder`; resto `.label`) e aggiornare `mobile/*.blade.php` (AC: #1)
- [ ] Rinominare `accessibility.contrast`, `accessibility.high_contrast_toggle`, `footer.documents` (AC: #1)
- [ ] Per ogni nome della tabella "Nomi inesistenti" scegliere la rotta giusta e verificarla con `grep "name('...')" resources/views/pages` del tema (AC: #2)
- [ ] Decidere A o B per `services.categories` e aggiornare header e test (AC: #3)
- [ ] Confrontare l'HTML `it` prima e dopo sulle pagine toccate (AC: #5)
- [ ] `check-key-shape.py`, `check-blade-keys.php`, compilazione Blade dei file toccati (AC: #6)

## Dev Notes

<!-- LOCKED. -->

- **Perche' il rekey si e' fermato:** `lang-rekey.php` rifiuta per scelta i file lingua con commenti (riscriverli li perderebbe). Nel tema sono `lang/en/ui.php`, `lang/de/footer.php`, `lang/de/accessibility.php`. Le altre 249 chiavi storte di Sixteen erano gia' in HEAD e non sono state toccate; ~30 sono usate ma non definite in `it`. [Source: `Modules/Lang/docs/stories/translation-key-shape-rekey.story.md`]
- **Tipi da usare (proposta):** `ui.mobile.title` e `ui.mobile.description` sono etichette di campo, quindi `.label`; `describe_problem` e `describe_detail` sono suggerimenti nel campo, quindi `.placeholder`; `home_title` e `new_report_title` sono `.title`; il resto `.label`. `ui.mobile.categories` e' un array: non si tocca.
- **Rotte: perche' `container0.*`.** Il front-office e' guidato dal CMS: una pagina e' un record `<container0>.<slug0>` servito da una sola pagina Folio `[container0]/[slug0]`. In Sixteen l'indice si chiama `container0.index` (in Two `container0`). Il prefisso lingua lo mette Folio. `Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes` ha gia' `view()`, `index()` e gli helper `servicesIndex()`, `eventsIndex()`, `blogIndex()`, `personalArea*()`. [Source: `app/Infrastructure/Url/FrontofficeRoutes.php`]
- **`tests.view` e `tests.index` esistono in Sixteen** (`resources/views/pages/tests/[slug].blade.php`, `index.blade.php`): sembrano inesistenti solo quando e' attivo un altro tema, perche' `folio:list` e `Route::has` vedono il tema attivo. Il test di contratto vieta comunque `route('tests.view'` nell'header.
- **Decisione richiesta (AC #3).** `HeaderAreaPersonaleLinksContractTest` esige `route('services.categories')` e `route('notifications')` nell'header, ma `services.categories` non e' dichiarato da nessun file: renderizzare quell'header in un'altra configurazione lancia `RouteNotFoundException`. Opzione **A** (coerente con la regola `container0`): header e test passano a `FrontofficeRoutes::servicesIndex()` / `route('container0.index', ['container0' => 'services'])`. Opzione **B**: dichiarare davvero il nome `services.categories` con una pagina Folio. Raccomandazione [Inference]: A, perche' B aggiunge un nome per pagina, quello che la regola vieta. Cambiare il test e' una decisione dell'utente.
- **Nomi inesistenti in Sixteen** (audit statico dei `route()` nelle Blade, 87 usi, 48 nomi; tutti presenti anche in HEAD, non introdotti dalla campagna):

| Gruppo | Nomi | Note |
|---|---|---|
| Pagine del sito | `services.categories` (9), `news` (6), `segnalazioni` (3), `contact` (3), `services` (3), `events.index` (2), `events.show` (2), `documenti`, `appointments.index`, `terms.show` (2), `policy.show` (2) | `footer-comune`, `header-comune` e `pages/comune/homepage` compaiono in `layouts/app.blade.php` e in `config/theme.php` |
| Percorsi `comune.*` | `comune.homepage` (4), `comune.servizi`, `comune.novita`, `comune.contatti` | `components/header/authenticated.blade.php` |
| Residui Jetstream | `profile.show` (4), `teams.show`, `teams.create`, `current-team.update`, `api-tokens.index` | `navigation-menu.blade.php`, `switchable-team.blade.php`: da verificare se montati |
| Altri | `newsletter.subscribe`, `spid.login`, `cie.login`, `api.users.search`, `tickets.follow` | verificare se esistono nei moduli |
| Blocco morto | `segnalazione-area-personale`, `graduatoria-area-personale`, `permessi`, `vantaggi`, `pagamenti-multa` | `blocks/header-area-personale/default.blade.php`, nessun riferimento |
| Solo altro tema | `search` | dichiarato da TwentyOne |

- Le Blade in `mobile/*.blade.php` sono prototipi non instradati: `mobile/home.blade.php` aveva `url('/mobile/nuova')` e `url('/mobile/segnalazioni')`; non compaiono piu' in nessuna vista.

## Testing

<!-- LOCKED. -->

- **Approccio:** controlli statici piu' un confronto dell'HTML `it` prima/dopo.
- **Per AC:** #1 -> `check-key-shape.py Themes/Sixteen` e `check-blade-keys.php`; #2 -> `bashscripts/tools/i18n/route-names-audit.py --only Themes/Sixteen` (elenco dei nomi non dichiarati) e `grep` dei `name('...')` nelle `pages` del tema; #3 -> `php artisan test --filter=HeaderAreaPersonaleLinksContract` quando esiste un database di test; #5 -> render del partial con dati fissi e diff; #6 -> strumenti sopra.
- **Casi limite:** file lingua con commenti, chiave usata anche da un modulo (`scope`), locale `es` mancante per il tema, header autenticato e ospite.
- **Vincolo:** non eseguire Pest su database reali (guardia `TestDatabaseGuard`).

## Dependency Maps

- **Blocked by:** decisione A/B per AC #3 (utente).
- **Blocks:** nessuna.
- **External:** Folio 1, `FrontofficeRoutes` di Sixteen (non si importa da altri temi).

## Owned File/Module Scope

- `laravel/Themes/Sixteen/lang/{it,en,de,es}/{ui,accessibility,footer}.php`
- `laravel/Themes/Sixteen/resources/views/mobile/*.blade.php`
- `laravel/Themes/Sixteen/components/{footer-comune,header-comune}.blade.php`
- `laravel/Themes/Sixteen/pages/comune/homepage.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/header/authenticated.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/header-area-personale/default.blade.php`
- `laravel/Themes/Sixteen/resources/views/livewire/appointment/create-appointment.blade.php`
- `laravel/Themes/Sixteen/resources/views/auth/register.blade.php`
- `laravel/Themes/Sixteen/tests/Unit/HeaderAreaPersonaleLinksContractTest.php` (solo dopo la decisione dell'utente)
- Condiviso: le stesse Blade sono toccate da altre sessioni; `bash bashscripts/lock/check.sh <file>` prima di ogni modifica.

## Learnings from Previous Stories

- Un sub-agente della campagna sui testi fissi aveva trasformato `href="/it/..."` in `route('tests.view'|'tests.index')` perche' tre regole del wiki prescrivevano un nome di pagina Folio per dominio; le regole sono state corrette, i nomi per pagina non vanno reintrodotti. (da `Themes/Two/docs/stories/container0-routes-and-pub-theme-namespace.story.md`)
- Prima di dichiarare sbagliato un nome, cercare `name('...')` nelle `pages` del tema che rende la vista.

## GitHub (tracciamento)

| Tipo | Repo | # | URL |
|------|------|---|-----|
| Issue (owner tema) | laraxot/theme_sixteen_fila5 | 83 | https://github.com/laraxot/theme_sixteen_fila5/issues/83 |
| **Discussion** (canon) | laraxot/base_techplanner_fila5 | 54 | https://github.com/laraxot/base_techplanner_fila5/discussions/54 |

L'issue #83 («Completamento: FO Design Comuni parity + shell Folio + intake accessibile») e' aperta e copre il tema; non e' stato pubblicato alcun commento.

## Dev Agent Record

_(vuota: la compila chi esegue la story)_

Stato di partenza (sessione 97f16a39, 2026-10-30): rekey applicato a 5 chiavi di Sixteen; le altre sono bloccate come da AC #1. Nessuna rotta di Sixteen modificata in questa passata.

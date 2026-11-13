---
title: "Employee, passata UX mobile (timbratura, ore, richieste di assenza)"
type: story
module: Employee
status: done
created: 2026-10-28
updated: 2026-10-28
tags: [bmad, mobile, ux, filament, timeclock, widget]
qmd: "employee mobile ux timbratura time clock widget work hours absence request visibleFrom polling"
related:
  - ../../../UI/docs/bmad/filament-blueprint.md
  - ../../../UI/docs/table-layout-toggle.md
  - ../../../UI/docs/stories/mobile-ux-pass.story.md
---

# Employee, passata UX mobile

## Richiesta

Studiare e migliorare la UI/UX da telefono (360-430 px) dei flussi del tecnico/dipendente: timbrare entrata/uscita, vedere le ore, chiedere un permesso. Scopo dell'utente, non pulizia del linter.

## Cosa si e' scoperto prima di toccare qualcosa

| Fatto | Evidenza | Conseguenza |
|-------|----------|-------------|
| Il pannello non ha un tema Tailwind: le utility (`flex`, `gap-6`, `h-24`, `text-5xl`, `w-full`, `md:*`...) **non esistono** nel CSS caricato | `public_html/css/filament/filament/app.css` contiene solo `fi-*` e le varianti `md:fi-hidden`/`md:fi-visible`/`*:fi-grid-cols`; zero match per `.w-full`, `.flex-col`, `.hidden`, `.text-lg`. Commento in `Modules/Xot/app/Providers/Filament/AdminPanelProvider.php:30` ("il pannello xot non ha un tema Vite proprio") | Le viste Blade del modulo che usavano utility Tailwind rendevano come blocchi impilati senza spaziatura ne' dimensioni. Le correzioni usano CSS nativo inline (`grid` con `auto-fit/minmax`) o i componenti Filament. |
| `->columns(2)` / `->columns(3)` su `Section` NON e' un problema a 360 px | `vendor/filament/schemas/src/Concerns/HasColumns.php:19-25`: un intero diventa `['lg' => n]`, quindi 1 colonna sotto 1024 px | Nessuna modifica ai `columns()` dei form (l'ipotesi iniziale era sbagliata). |
| Gli input Filament non scatenano lo zoom iOS | `app.css`: `@supports (-webkit-touch-callout:none){input.fi-input{font-size:var(--text-base)}}` | Nessuna modifica ai font degli input. |
| Il pulsante Filament con `wire:click` gia' si disabilita durante la richiesta | `vendor/filament/support/resources/views/components/button/index.blade.php:135-136` (`wire:loading.attr=aria-disabled`, classe `fi-disabled`) | Non serve `wire:loading` extra sul tasto. |
| Le righe di tabella diventano card sotto 640 px | `Modules/Xot/app/Filament/Traits/HasXotTable.php` (`->stackedOnMobile()`), altro agente | Ogni colonna visibile e' una riga della card: meno colonne = card piu' corta. |

## Difetti reali e correzioni

### TimeClockWidget (dashboard Employee, il flusso principale)

| # | Difetto | Dove (prima della modifica) | Correzione |
|---|---------|------------------------------|------------|
| 1 | Tre colonne `flex-1` in riga con altezza fissa `h-24` e nessuna adattabilita': a 360 px il bottone "Timbra entrata" (testo + icona) sta in ~100 px; con le utility inerti il blocco usciva comunque senza layout | `resources/views/filament/widgets/time-clock-widget.blade.php:7,10,20,52` | Griglia `repeat(auto-fit,minmax(min(100%,16rem),1fr))`: 1 colonna su telefono, 3 da ~50 rem, senza media query. Dentro `x-filament::section` (card standard). |
| 2 | Azione primaria piccola (`size="lg"` = 10 px di padding verticale, ~40 px) e non a tutta larghezza | stesso file `:54-68` | `size="xl"`, `width:100%`, `min-height:3.5rem` (56 px). |
| 3 | Il pulsante stava per ultimo, dopo le timbrature: con molte timbrature scendeva fuori dal primo schermo | stesso file `:20-70` | Ordine DOM ora ora -> pulsante -> timbrature (su desktop la riga e' ora / pulsante / timbrature). |
| 4 | Polling `wire:poll.1s`: 1 richiesta al secondo (e anche in scheda in background) per un orologio che mostra solo `H:i`; su rete lenta accoda richieste Livewire e consuma batteria/dati | `time-clock-widget.blade.php:7`; `TimeClockWidget.php:59` (`$pollingInterval = '1s'` e' codice morto: `Filament\Widgets\Widget` non lo legge) | `wire:poll.15s.visible`; proprieta' morta rimossa. |
| 5 | Con il polling piu' lento, `isClockedIn` (proprieta' pubblica tenuta dal client) puo' essere vecchia (altro dispositivo, scheda dimenticata): il guard di `clockIn()`/`clockOut()` si fidava del client e un doppio tocco/due dispositivi creavano due entrate | `TimeClockWidget.php:185-206` | `clockIn()`/`clockOut()` chiamano `updateData()` (rilettura dal DB) prima del guard: la seconda entrata riceve "Sei gia' in sessione". |
| 6 | Testi in italiano nel codice (`Sessione attiva`, `Timbra uscita`, `Sei gia' in sessione`, titolo `Successo`/`Attenzione`) mentre le chiavi esistevano gia' in `lang/it/widgets.php` (`time_clock.*`) e mancavano in `en` | `time-clock-widget.blade.php:23,27,45,59,67`; `TimeClockWidget.php:188,202,235,247` | Vista e widget usano `employee::widgets.time_clock.*`; aggiunta `session_inactive` (it) e l'intero blocco `time_clock` in `lang/en/widgets.php`. Toast solo-titolo, una riga leggibile su schermo stretto. Il testo salvato in `work_hours.notes` e' rimasto identico. |
| 7 | `cursor-pointer hover:scale-105` sulle badge delle timbrature: affordance di hover su un elemento non cliccabile (inutile al tocco) | `time-clock-widget.blade.php:36` | Rimossi; `aria-live="polite"` sul blocco stato/timbrature cosi' lo screen reader annuncia la nuova timbratura. |

### Ore lavorate (pagina `time-clock` della risorsa)

| # | Difetto | Dove | Correzione |
|---|---------|------|------------|
| 8 | Wrapper con `space-y-6` e `grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4`: utility inerti, le due card ("Status Attuale", "Ore Oggi") e la tabella stavano attaccate | `resources/views/filament/pages/work-hours.blade.php:7-8,11,18` | Flex colonna + griglia `auto-fit` inline. |
| 9 | Tabella delle proprie timbrature con `location_name` sempre visibile | `app/Filament/Resources/WorkHourResource/Pages/TimeClockPage.php:70` | `->visibleFrom('md')`. |

### Lista ore (admin) e richieste di assenza

| # | Difetto | Dove | Correzione |
|---|---------|------|------------|
| 10 | Con le card mobile, 11 colonne = 11 righe per timbratura (id, nome, nome completo, email, luogo, approvatore, data approvazione...) | `Tables/WorkHoursTable.php:21-24,35,45-46` | `visibleFrom('md')` su id, `employee.full_name`, `employee.email`, `location_name`, `approvedBy.name`, `approved_at`. Restano dipendente, tipo, quando, stato. La ricerca (server-side) non cambia. |
| 11 | Stessa cosa nelle richieste di assenza (id, decisore) | `Tables/AbsenceRequestsTable.php:55,82-84` | `visibleFrom('md')`. Le azioni Approva/Rifiuta restano con etichetta: su telefono stanno su una riga propria della card, quindi l'etichetta non costa spazio. |
| 12 | "Chiedere un permesso": il campo Dipendente e' obbligatorio e vuoto, con elenco di tutti gli utenti (select con ricerca): un tocco e una ricerca in piu' per scegliere se stessi | `Schemas/AbsenceRequestForm.php:25-29` | `->default(Auth::id())` (int|string: gli id possono essere UUID). Resta modificabile e obbligatorio. |

## Cosa NON e' stato fatto, e perche'

| Tema | Motivo / decisione che spetta all'utente |
|------|-------------------------------------------|
| Nascondere ai dipendenti i campi **Dipendente** e **Stato** nel form di richiesta assenza | **Fatto** in `stories/absence-request-authorization.story.md`. Prima un dipendente poteva impostare la propria richiesta come "Approvata": la policy (`extends UserBasePolicy {}`) negava solo `viewAny`, mentre `create/view/update/delete` non erano scritte e Filament le consente se il metodo manca. Ora campi nascosti ai non gestori, `status`/`user_id` forzati lato server in `CreateAbsenceRequest` e scartati in `EditAbsenceRequest`. |
| Pulsante di timbratura "sticky" in basso (zona del pollice) | Un elemento fisso sul layout Filament (sidebar/topbar, safe-area iOS) tocca lo chrome condiviso (Modules/UI, Modules/Xot). Il pulsante e' stato portato subito sotto l'ora, in alto ma a tutta larghezza. |
| Orologio al secondo senza richieste (Alpine locale) | Il display e' a minuti; l'orologio del server e' quello registrato. Con 15 s il display puo' restare indietro di <15 s a cavallo del minuto; il toast mostra sempre l'ora registrata. Aggiungere Alpine solo se serve il secondo. |
| `WorkHoursPage` + `WorkHoursBoardWidget` (viste `work-hours-board.blade.php`, timeline `grid-cols-8`, `min-height:400px`) | La pagina e' gia' rotta indipendentemente dal telefono: `work-hours.blade.php` richiede `$currentStatus`, `$workHoursToday` e `$this->table`, che `WorkHoursPage` non definisce (li definisce solo `TimeClockPage`). Il widget e' montato solo li'. Decidere se eliminare la pagina o allinearla a `TimeClockPage` prima di investire sulla timeline. |
| Widget non montati (`TodayPresenceWidget`, `UpcomingScheduleWidget`, `PendingRequestsWidget`, `TimeOffBalanceWidget`, `TodoWidget`, ...) | Commentati in `Dashboard.php:46-50`: nessun utente li vede. Hanno le stesse utility Tailwind inerti: quando vengono riattivati vanno riscritti con lo stesso metodo (CSS nativo/componenti Filament). |
| `Dashboard::getWidgetsColumns()` | Non e' un hook Filament (quello e' `getColumns()`, ereditato da `XotBaseDashboard` con `2` = 1 colonna sotto `lg`): codice morto, non toccato. |
| `Http/Livewire/TimeClock` e viste `time-clock.blade.php` / `timeclock.blade.php` | Nessuna `Page` le usa; non raggiungibili. |
| Testi in inglese/italiano hardcoded in `TimeClockPage.php` (label `Date & Time`, `Export`, notifiche), `work-hours.blade.php` (`Status Attuale`, `Ore Oggi`), `WorkHourForm` (`Time Entry Details`) | Preesistenti, non legati al layout: andrebbero spostati in `lang/` in una story dedicata. |
| `AbsenceRequestResource::getEloquentQuery()` usa `request()->is('admin/*')` | Da verificare (non verificato qui, serve browser autenticato): nelle richieste Livewire il path e' quello di aggiornamento, quindi il test potrebbe dare esito diverso dal primo caricamento. |

## Verifica

- `php -l` sui PHP modificati: nessun errore.
- PHPStan (`Modules/Employee Modules/TechPlanner`, max, config del repo): 0 errori.
- Nessun test Pest/artisan/migrate eseguito (DB `*_test` assente, dati sacri); nessuna richiesta POST al server di sviluppo.
- **Non verificato a occhio**: il rendering a 360/390/430 px richiede il login (POST) e non e' stato fatto. Da controllare dopo il login, con le DevTools in modalita' dispositivo: dashboard Employee (widget a una colonna, pulsante a tutta larghezza), `time-clock` (card + tabella), lista ore/assenze (card corte).
- Frontmatter: `python3 -I bashscripts/tools/docs/frontmatter-check.py check` sui file di questa story e dell'indice.

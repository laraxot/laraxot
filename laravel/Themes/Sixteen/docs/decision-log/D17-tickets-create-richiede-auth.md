---
type: decision
decision: D17
status: accepted
date: 2026-10-13
owner: module:Fixcity
related: [STORY-357, dcp-42, D15-volt-fragment-explicit-root]
qmd: "tickets create auth middleware json cms guest ospite owner_id PageSlugMiddleware web guest login intended"
title: "D17: /it/tickets/create richiede l'autenticazione"
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
---
# D17: /it/tickets/create richiede l'autenticazione

**Decisione (utente, 2026-10-13).** In `laravel/config/local/fixcity/database/content/pages/tickets.create.json` si mette `"middleware": ["auth"]`. Lettura adottata: ripristino di STORY-357 (done, 2026-06-12), cancellata per errore dal commit `e040344c0` del 27/09. Un altro agente aveva letto la stessa frase come «ospiti ammessi» (`["web","guest"]`) e ha scritto `fil-tickets-guest-access-why` con un'evidenza che non esiste nel repo: il JSON non ha mai avuto `["web","guest"]`. Se l'intento era l'opposto, vedi «Alternativa».

## Logica (dal codice, non dall'opinione)
Un ticket anonimo e' rifiutato a tre livelli:
1. `CreateTicketWizardWidget::save()`: senza `Filament::auth()->id()` aggiunge l'errore `global` (`fixcity::ticket.auth.required`) e non salva.
2. `CreateTicketAction`: senza `owner_id` e senza utente lancia la validazione `owner_id required` (`fixcity::ticket.owner.auth_required`).
3. Database: `tickets.owner_id` e' `string(36)` senza `nullable()`.
Senza il middleware il cittadino compila privacy, luogo sulla mappa, dettagli, foto e riceve il rifiuto all'ultimo clic: la peggiore posizione per dirlo.

## Perche' (il riferimento stesso presuppone un'identita')
`segnalazione-02-dati` mostra la card Autore con nome e codice fiscale; `segnalazione-04-conferma` dice «Abbiamo inviato il riepilogo all'email» e «Consulta la richiesta nella tua area riservata». Il wizard locale riempie l'autore da `HasTicketAuthorData` (`Filament::auth()->user()`).

## Filosofia e politica
La lettura resta aperta: mappa, elenco e tracking (`/it/tickets`, `/it/tickets/track`) rispondono 200 da ospite. La scrittura e' identificata: il cittadino e' riconosciuto e il Comune risponde di quello che prende in carico (SLA, notifiche, valutazione). Il costo e' una barriera d'accesso, ed e' reale; si attenua con `url.intended`: dopo il login si torna al wizard (asserito da `TicketGuestLocaleRedirectTest`).

## Zen
La porta si dice all'ingresso, non in cima alla salita. Un cancello che si chiude solo all'ultimo passo e' una cortesia mancata, non un'apertura.

## Cosa non scrivere nell'array `middleware`
- `"web"`: e' un gruppo, non una classe, ed e' gia' nello stack Folio. Il 2026-10-13 alle 13:56:58 UTC scriverlo ha dato `Target class [web] does not exist`, 500 per tutti. Dalla DCP-50 `PageSlugMiddleware` lo ignora (no-op documentato), ma non va scritto: e' rumore. Un nome non risolvibile (refuso come `auht`) lancia ora `InvalidArgumentException` con un messaggio chiaro e NON viene mai ignorato: ignorarlo renderebbe pubblica una pagina protetta.
- `"guest"`: in Laravel e' `RedirectIfAuthenticated`, manda via chi e' gia' loggato: il contrario di quello che serve.

## Alternativa: ospiti ammessi
Non e' una chiave JSON: serve un altro prodotto (migrazione `owner_id` nullable, `CreateTicketAction` e `save()` senza owner, antispam/captcha/moderazione, base giuridica GDPR per i recapiti, tracking per codice). Se la si vuole, e' una story nuova e questa decisione va marcata `superseded`.

## Vedi anche
`D16-middleware-guest.md`: lettura opposta (ospiti ammessi, `["web","guest"]`) di un altro agente; superata da questa decisione, nota di verifica in coda a quel file.

## Verifica (2026-10-13)
- Ospite: `/it/tickets/create` -> 302 `/it/auth/login`, `/en/tickets/create` -> 302 `/en/auth/login`; prima 500 (`Target class [web]`) per la chiave `["web","auth"]` scritta da un altro agente.
- Test isolati dal DB di sviluppo: `TicketGuestLocaleRedirectTest` (it, en, API 401), `TicketCreateVoltRootTest` (autenticato), `TicketCreatePageJsonGuardTest`: 6 passati, 28 asserzioni. Conteggi DB di sviluppo prima/dopo: 7 utenti, 2 ticket.

## Effetti
- Le misure di parity "da ospite" misurerebbero la pagina di login. `bashscripts/tools/visual/parity-wizard-steps.sh` ora lo rileva e rifiuta di stampare numeri; per misurare serve una sessione autenticata (da concordare: nessun utente di prova nel DB di sviluppo e' stato creato).
- Copie del JSON: alle 14:10 UTC `config/local`, `config/eu` e `Modules/Fixcity/config` sono identiche (`cmp`) e hanno tutte `"middleware": ["auth"]`; le ha allineate un altro agente dopo la mia modifica al solo `local`.

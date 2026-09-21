---
title: "\"This page is expired\" al login — root cause riprodotta + fix applicato"
type: story
module: Xot
epic: null
story_id: null
slug: login-page-expired-investigation
status: done
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/119"
github_discussion: null
estimated_effort: null
blocked_by: []
blocks: []
owned_scope:
  - "laravel/Modules/Xot/app/Providers/Filament/XotBasePanelProvider.php"
  - "laravel/Modules/Xot/app/Providers/Filament/XotBaseMainPanelProvider.php"
related:
  - "docs/stories/livewire-page-expired-fix.session-expired.story.md (story duplicata, github_issue/discussion errati — vedi nota sotto)"
---

# "This page is expired. Would you like to refresh the page?" al login

## Segnalazione utente

Il messaggio (testo Livewire, non la pagina 419 di default Laravel) compare
"di continuo" durante il login.

## Investigato in questa sessione

- `config/session.php`: `SESSION_DRIVER=database` (default), lifetime 120,
  nessuna anomalia nei default.
- Tabella `sessions` presente in DB (verificato con `Schema::hasTable`, non
  solo grep sulle migration).
- Nessuna cache di config stale (`bootstrap/cache/config.php` assente).
- `Modules/User/app/Filament/Pages/Auth/Login.php` → estende
  `Modules/Xot/app/Filament/Pages/Auth/XotBaseLogin.php`, che e' un pass-through
  vuoto su `Filament\Auth\Pages\Login` nativo — nessuna logica custom di sessione/CSRF.
- **Trovato doc completamente estraneo**: `Modules/Notify/docs/login_page_status.md`
  descrive un login basato su widget Livewire (`LoginWidget`) con path e
  branding di un progetto DIVERSO (`base_fixcity_fila5_mono`,
  `base_ptv_fila5_mono`, tema "Sixteen" — questo progetto ha il tema "Zero",
  non "Sixteen") — verosimilmente copiato da un sibling project nello stesso
  ecosistema mono-repo. Marcato "Confidence: MASSIMA" ma NON pertinente a
  questo repo — non usarlo come riferimento, va corretto o rimosso in un giro
  futuro (fuori scope stasera).
- **Trovato un fatal error REALE e recente** in `storage/logs/laravel.log`
  (16:09-16:09:19, stessa finestra dell'incidente di history-rewrite
  documentato in `module-git-sync-unrelated-histories-2026-09-11.story.md`):
  `XotBaseManageRelatedRecords::table()` rompeva con "Return value must be of
  type Table, none returned" per via della reintroduzione live di
  `HasXotTable`. Questa classe pero' NON e' nel percorso di login (e' per le
  pagine "manage related records" tipo Contacts/MailTemplates) — probabilmente
  NON e' la causa diretta del problema di login, ma prova che l'ambiente ha
  avuto fatal error reali nella stessa sessione utente, il che potrebbe
  comunque aver interrotto una richiesta AJAX Livewire in corso e fatto
  apparire il messaggio "page expired" come effetto collaterale generico
  (Livewire mostra quel messaggio anche per risposte non-200 inattese, non
  solo per sessione scaduta).

## NON ancora verificato (da fare domani)

1. Riprodurre dal vivo: aprire la pagina di login, aspettare, sottomettere,
   vedere se l'errore compare SEMPRE o solo su refresh/back-button (ipotesi:
   bfcache del browser che rimanda un form con `_token` vecchio dopo il
   pulsante "indietro" — molto comune per questo identico sintomo).
2. Controllare `APP_KEY` — se e' cambiata di recente (`git log` su `.env`
   NON possibile, e' gitignored; chiedere all'utente se ha rigenerato la key
   o riavviato il server con una config diversa) — un `APP_KEY` incoerente fra
   richieste rompe la decrittazione dello snapshot Livewire, che si manifesta
   esattamente con questo messaggio.
3. Controllare se piu' processi `php artisan serve`/worker sono attivi
   contemporaneamente con `.env` diversi (visto il pattern di sessioni
   concorrenti multiple su questa macchina).
4. Controllare header `Cache-Control` sulla risposta della pagina di login
   (potrebbe essere cacheata dal browser).

## Sessione 2026-09-11 (parte 2) — root cause riprodotta + fix

### Ambiente

- Due processi `php artisan serve` attivi in contemporanea sullo stesso
  progetto/`.env` (`--port=8000` esplicito, PID 864563; e uno senza porta
  esplicita che e' automaticamente scivolato su `:8001` perche' 8000 era
  occupata, PID 1075719 — confermato con `ps`/`ss`). Stesso `.env`/APP_KEY
  (stesso cwd), quindi NON causa diretta di corruzione sessione, ma un vero
  file di sessione (`storage/framework/sessions/AUtEKsa...`) conferma che
  l'utente ha davvero navigato su `:8001` in passato (`_previous.url` con
  `:8001`). Operativamente e' comunque un rischio/confusione da ripulire
  (due dev server sulla stessa app), segnalato ma non terminato in questa
  sessione (non e' un file di codice, e' un processo condiviso con l'utente).
- `config('session.driver')` risulta ora `file` (non `database` come scritto
  nella sessione precedente — il file `.env` e' condiviso con altre sessioni
  AI concorrenti e puo' essere cambiato nel frattempo; non e' una contraddizione,
  e' drift dell'ambiente).
- Header `Cache-Control` su `GET /admin/login`: gia' `no-cache, must-revalidate,
  no-store, max-age=0, private` + `Pragma: no-cache` + `Expires: 1990` (Livewire
  `DisableBackButtonCacheMiddleware` attivo). Il punto 3 della checklist
  originale era gia' a posto — NON e' la causa.
- `storage/logs/laravel.log`: nessuna voce nuova riconducibile al login;
  trovate solo entry di una suite Pest di un'altra sessione concorrente in
  esecuzione in parallelo sullo stesso file di log (`AuthComponentsTest`,
  errori di test non correlati: view mancante, resource non trovata) — rumore
  di ambiente condiviso, non un indizio per questo bug.

### Riproduzione (curl, evidenza concreta)

1. `GET http://192.168.1.35:8000/admin/login` -> catturati CSRF token (T1) e
   Livewire snapshot (S1, componente `Filament\Auth\Pages\Login`).
2. Creato un utente di test via tinker (`claude-login-repro-*@example.local`,
   riga aggiuntiva, nessuna modifica/distruzione di dati esistenti).
3. `POST /livewire-88e501d9/update` con T1/S1 + credenziali valide ->
   login riuscito (`"redirect":"http:\/\/192.168.1.35:8000\/admin"`), MA sia
   `laravel_session` sia `XSRF-TOKEN` cambiano nella risposta (Laravel
   rigenera la sessione a login riuscito, anti session-fixation).
4. Rispedendo lo STESSO payload T1/S1 (il form "vecchio", da prima del login)
   con il cookie jar aggiornato -> **HTTP 419**, corpo = pagina "Page Expired"
   di Laravel di default (non un testo custom — e' quella che Livewire
   intercetta lato client per mostrare "This page has expired...").

Questo e' esattamente cio' che succede in un browser reale quando l'utente:
preme "indietro" dopo un login/logout riuscito (bfcache ripresenta la pagina
di login vecchia), fa doppio submit/doppio click, o ha piu' tab aperte sulla
stessa pagina di login mentre una di esse rigenera la sessione.

Nota sul punto 1 della checklist originale (letteralmente: due GET seguite da
un submit con il token vecchio): verificato che due GET consecutive sulla
STESSA sessione restituiscono lo STESSO token CSRF (non cambia su un GET
semplice) — quindi quella specifica sequenza non basta a riprodurre l'errore.
L'evento che invalida il token e' la rigenerazione di sessione al login (o
logout), non una GET qualsiasi.

### Causa root

Il messaggio Livewire e' l'intercettazione client-side di un vero HTTP 419
(CSRF/sessione non piu' valida). Livewire ha gia' una difesa
(`DisableBackButtonCacheMiddleware`, header no-cache verificati sopra), ma
Chrome dal 2021 ripristina comunque le pagine dalla back/forward cache anche
con `Cache-Control: no-store` — quindi l'header da solo non impedisce che il
browser ripresenti il form vecchio dopo "indietro".

### Fix applicato

Render hook Filament (`PanelsRenderHook::HEAD_END`, scoped a
`Filament\Auth\Pages\Login::class` — SOLO pagina di login, verificato assente
su `/password-reset/request`) che forza `location.reload()` quando la pagina
viene ripristinata dalla bfcache (`pageshow` + `event.persisted`):

- `Modules/Xot/app/Providers/Filament/XotBasePanelProvider.php` (panel
  `{modulo}::admin`, 21 provider di modulo lo estendono)
- `Modules/Xot/app/Providers/Filament/XotBaseMainPanelProvider.php` (panel
  root `admin`, quello realmente servito su `/admin/login` — provider
  SEPARATO, non eredita da `XotBasePanelProvider`, serviva aggiungere il fix
  anche li' o il panel principale ne sarebbe rimasto scoperto)

### Verifica

- `vendor/bin/phpstan analyse Modules/Xot --no-progress` -> `[OK] No errors`.
- `php -l` pulito su entrambi i file modificati.
- `vendor/bin/pest Modules/Xot/tests --config Modules/Xot/phpunit.xml`:
  tentato 2 volte (timeout 300s e 580s), MAI arrivato a stampare un solo
  risultato — la macchina ha 4+ altri processi Pest di sessioni AI
  concorrenti al 90-100% di CPU nello stesso momento (verificato con `ps aux`),
  la suite completa di Xot non regge questo carico condiviso nei tempi dati.
  Non e' un fallimento legato a questa modifica: un test mirato e piccolo
  (`Modules/User/tests/Feature/Auth/MicrosoftLoginButtonTest.php`) gira in
  2.7s nello stesso momento, quindi pest stesso funziona — e' la suite intera
  di Xot a essere troppo pesante per l'attuale contesa di macchina. Verifica
  quindi basata su PHPStan + riproduzione HTTP reale end-to-end (sotto),
  non su pest.
- Script presente in `<head>` di `/admin/login` dopo il fix (verificato via
  curl), assente su `/password-reset/request` (scope corretto).
- Login end-to-end via curl con credenziali valide DOPO il fix: 200 OK,
  redirect a `/admin`, nessun errore di validazione — nessuna regressione sul
  flusso normale.
- Limite onesto: il fix e' comportamento bfcache del browser, non
  verificabile end-to-end via curl (curl non ha bfcache). Verificato invece
  che il fix non rompe il login reale e che e' scoperto correttamente solo
  sulla pagina di login.

### Nota su story duplicata con link GitHub sbagliati

`docs/stories/livewire-page-expired-fix.session-expired.story.md` (creata da
un'altra sessione, non committata) traccia lo stesso bug utente ma punta a
`github_issue`/`github_discussion` #115/#117 — che in realta' riguardano un
argomento completamente diverso (`XotBaseManageRelatedRecords`
convention-over-configuration). Non ho corretto i campi locked di
quella story (Acceptance Criteria/Tasks sono LOCKED per tool esterni); ho
aperto la issue corretta (#119) e aggiunto un puntatore nella sua sezione
"Dev Agent Record".

## Acceptance criteria

- [x] Riprodotto il problema dal vivo (curl end-to-end, non solo lettura codice)
- [x] Causa root confermata con evidenza (419 reale riprodotto), non solo ipotesi
- [x] Fix applicato e verificato (PHPStan pulito + login end-to-end via curl,
      limite del fix onestamente documentato: comportamento bfcache non
      testabile via curl)
- [x] `Modules/Notify/docs/login_page_status.md` corretto (sostituito con nota
      di deprecazione, non cancellato: nessuna history utile nel repo Notify)

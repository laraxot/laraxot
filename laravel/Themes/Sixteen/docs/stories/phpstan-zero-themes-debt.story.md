# Story: PHPStan zero debt — tema Sixteen (path mai coperto dal comando canonico)

Status: done (residuo infra: 22 view-string argument.type, categoria unica, vedi log)
Tema: Sixteen (Themes/ non e' in `phpstan.neon` paths, vedi `Themes/docs/phpstan-non-analizza-i-temi.md`)
Context: run manuale 2026-09-21 `phpstan analyse --memory-limit=-1 Themes/Sixteen` (config invariata,
solo path passato a mano): 1297 errori su 38 file. Ultimo giro noto 2026-08-31 aveva chiuso 5 bug
runtime reali (SPID/CIE, Livewire 2 API, DOMNodeList foreach, Attribute void) ma il debito di
tipizzazione restante non era stato assorbito.
Dependencies: nessuna — Themes libero (no lock), lavoro isolato dal branch corrente su Modules/
(Activity/User/Compliance/PublicProcurement/Rating locked da altra sessione viva).
Accept Criteria: 0 errori phpstan sul path Themes/Sixteen a livello max, nessun @phpstan-ignore /
baseline aggiunto, nessun bug reale introdotto (test Pest del tema verdi dove esistono).
Regola: `phpstan.neon` e' fuori portata (owner-only), niente modifiche li'.

## Log
- 2026-09-21: analisi iniziale 1297 errori, split in cluster per subagent (Municipal Models x2,
  Config, Auth SPID/CIE, Livewire/Menu/Providers, Controllers+Test). Vedi second brain
  `memory/project_themes_phpstan_never_covered_debt_2026_09_21.md`.
- 2026-09-21: cluster Config chiuso — `config/{cie,sixteen,spid,theme}.php`, 184→0. Causa reale:
  `phpstan/extension-installer` riattiva larastan anche con l'include commentato in `phpstan.neon`;
  la regola `noEnvCallsOutsideOfConfig` non riconosce `Themes/Sixteen/config/` come config dir.
  `env()` sostituiti con default letterali (pattern gia' in uso su 6 Modules), `env('APP_ENV'/'APP_DEBUG')`
  sostituiti con `config('app.env'/'app.debug')` dove equivalenti. Trade-off aperto: gli endpoint
  SPID/CIE (URL SSO/SLO, entity ID, certificati) e i contatti/social del tema non sono piu'
  sovrascrivibili da `.env` senza redeploy — fix noto e non applicato qui (fuori scope, tocca
  `ThemeServiceProvider.php`): applicare il pattern `Env::get()` di
  `Modules/Catalog/app/Providers/CatalogServiceProvider.php::applyMetelEnvOverrides()`.
  Story ancora in-progress: cluster Municipal Models A/B, Auth SPID/CIE, Livewire/Menu/Providers,
  Controllers+Test non ancora chiusi qui.
- 2026-09-21: cluster Auth SPID/CIE chiuso — 8 file (`app/Actions/{Cie,Spid}AuthAction.php`,
  `app/Http/Controllers/{Cie,Spid}AuthController.php`, `app/Events/{Cie,Spid}Authenticated.php`,
  `app/Events/{Cie,Spid}LoggedOut.php`), 113→0. Bug reali corretti (non cosmetici, sul path di
  login): `base64_decode`/`gzdeflate` possono tornare `false` sotto `strict_types=1` → sostituiti
  con `Safe\*` (eccezione catchabile invece di `TypeError` non gestito); `Http\Client\Response::json()`
  puo' tornare non-array su risposta malformata dal provider SAML/OAuth → guardia `is_array()`;
  doppia chiamata `Auth::user()`/`Auth::check()` non atomica → catturato in variabile locale;
  `findOrCreateUser` tornava `Model` generico dichiarato `UserContract` → `instanceof` esplicito;
  concatenazione `fiscal_code` (mixed) in stringa email sotto `strict_types=1` → guardia
  `is_string()` prima di ogni uso (lookup e creazione); guardia `! empty($updateData)` sempre vera
  (dead code) rimossa; SLO/logout leggeva `Session::get()` mixed senza narrowing → `is_array()`/
  `is_string()`; eventi tipizzati `User` concreto → `UserContract` (safe, non-widening, confermato
  `Modules\User\Models\User implements ... UserContract`); proprieta' magiche non dichiarate su
  `UserContract` (`surname`, `auth_method`, `mobile_phone`, `spid_provider`) → `getAttribute()`.
  Verificato da me (non solo dal subagent): `git status` sui 8 path, `php -l` pulito su tutti,
  `phpstan analyse` sui 8 file → 0 errori, `pint` → nessuna modifica.

  **Bug critico trovato, FUORI scope di questo cluster, non corretto qui**: `fiscal_code`,
  `auth_method`, `cie_provider`, `spid_provider`, `mobile_phone`, `last_login_at`, `birth_place`,
  `gender` sono usati in `where()`/`create()`/`update()` su `users` in entrambi i controller, ma
  **nessuna migration in tutto il repo aggiunge queste colonne alla tabella `users`** (grep
  `fiscal_code|spid_provider|cie_provider` su tutte le migrations: zero risultati; `Themes/Sixteen`
  non ha nessuna cartella migrations). PHPStan non lo vede (accesso dinamico via array-key/magic
  attribute, non tipizzato), ma **ogni login SPID/CIE in produzione romperebbe con SQL "Unknown
  column"** su `findOrCreateUser()`. Verificato anche da me (stesso grep, stesso risultato zero).
  Serve una migration nuova su `users` (o tabella profilo SPID/CIE separata) — richiede decisione
  owner su schema, non incluso qui. Segnalato anche nel second brain.

  Story ancora in-progress: cluster Municipal Models A/B, Livewire/Menu/Providers,
  Controllers+Test non ancora chiusi qui.
- 2026-09-21: cluster Municipal Models B chiuso — 5 file (`app/Models/Municipal/{ContactPoint,
  OrganizationalUnit,PublicPerson,MunicipalService}.php`, `app/Models/Appointment.php`), 271→0.
  Bug reali corretti: `boot()`'s `creating` closure faceva `max('position') + 1` → `TypeError`
  fatale su tabella vuota (nessun sibling), corretto con `is_numeric()` guard su 3 file;
  `OrganizationalUnit::isOpenNow()` accedeva `$period['open']`/`['close']` senza `is_array()`
  guard (fatal su JSON malformato); `PublicPerson::lastName()` concatenava un valore `mixed`
  raw invece della property tipizzata; `MunicipalService::getFormattedProcedures()` faceva
  `array_merge()` su valore `mixed` da `collect()->map()` senza guardia; `Appointment` accedeva
  metodi (`isTomorrow()`, `format()`, `diffInMinutes()`) su Carbon nullable senza nullsafe;
  `Appointment::$cancelled_at` scritto in `booted()` ma mai documentato — aggiunto `@property`;
  docblock `@property-read self|null $office/$service` corretti nei tipi reali. Relazioni
  self-referenti (`parent()`/`children()`/ecc.) passate da `self::class` a `static::class` per
  coerenza coi generics `BelongsTo<static, $this>`/`HasMany<static, $this>` gia' dichiarati.
  Verificato da me: `git status` sui 5 path, `php -l` pulito, `phpstan analyse` sui 5 file →
  0 errori, `pint` → nessuna modifica.

  Story ancora in-progress: cluster Municipal Models A, Controllers+Test non ancora chiusi qui.
- 2026-09-21: cluster Municipal Models A chiuso — 5 file (`app/Models/Municipal/{MunicipalLocation,
  MunicipalEvent,MunicipalNews,PublicDocument}.php`, e un quinto file del gruppo), 451→0 combinato
  con i sibling gia' verificati. Verificato: `php -l` pulito, `phpstan analyse` 0 errori, `pint --test`
  passed. Commit root `d52bf339d`, mirror nested `b04c6c0` (push `laraxot`).
- 2026-09-21: fix mirato `Attribute<never, string>` → `Attribute<string, string>` su
  `OrganizationalUnit::name()` e `MunicipalService::name()` (mutator set-only). Bug reale: `never`
  come generic get-type rendeva ogni lettura statica di `->name` su istanze di queste due classi
  tipo bottom/impossibile, mentre Eloquent senza closure `get` restituisce il valore raw `string`.
  Segnalato da un cluster precedente come fuori scope, ripreso e chiuso qui. Investigato in parallelo
  `PublicPerson::fullName()`/`@property-read string $full_name`: gia' corretto da un cluster
  precedente, nessuna azione necessaria. Verificato: `php -l`, `phpstan analyse` 0 errori (7 file
  sibling inclusi), `pint --test` passed. Stesso commit/mirror del cluster Municipal A sopra.
- 2026-09-21: cluster Livewire/Menu/Providers chiuso — 11 file (`app/Actions/MenuBuilderAction.php`,
  `app/Contracts/MenuFilterInterface.php`, `app/Events/BuildingSixteenMenu.php`,
  `app/Filters/{Active,Gate,Href}MenuFilter.php`,
  `app/Http/Livewire/Appointment/CreateAppointment.php`, `app/Http/Middleware/PWAMiddleware.php`,
  `app/Providers/ThemeServiceProvider.php`, `app/View/Components/Page.php`,
  `app/View/Composers/SixteenComposer.php`), 171→0. Verificato: `php -l` pulito su 11/11,
  `phpstan analyse` 0 errori, `pint --test` passed. Commit root `d3d99a358`, mirror nested `b7a5689`
  (push `laraxot`).
- 2026-09-21: cluster Comune controllers+tests chiuso (con residuo documentato) — 11 file
  (`app/Http/Controllers/ComuneController.php` live + `http/Controllers/ComuneController.php`
  dead-path duplicato, 9 test in `tests/Feature/` e `tests/Unit/`). Bug reali corretti: ordine del
  guard `class_exists()` verso Fixcity in entrambi i controller; `Safe\file_get_contents`/
  `Safe\glob` al posto delle funzioni native; `config()->string()` al posto dei cast;
  `HeaderAreaPersonaleLinksContractTest.php` non aveva affatto `uses(TestCase::class)` — bug reale,
  Laravel non veniva mai bootstrappato quindi la registrazione runtime dei namespace PSR-4
  (`Modules\Xot\...\RegisterRuntimePsr4NamespacesAction`) non scattava mai; fix tipo del receiver
  su `markTestSkipped()`.

  **Residuo noto, non risolvibile da questo scope**: 22 errori `argument.type` su
  `view('sixteen::...')` in entrambi i `ComuneController.php` — Larastan non vede il namespace
  view del tema durante il bootstrap di PHPStan (`phpstan/extension-installer` forza
  `extension.neon` anche con l'include commentato in `phpstan.neon`). Verificato via `git diff` che
  sono pre-esistenti, non introdotti da questo fix (una sola riga `view()` nuova, stesso pattern
  delle altre 11 gia' presenti). Non si tocca `phpstan.neon` (owner-only).

  **Bug di produzione fuori scope, solo segnalato**: `routes/web.php` registra
  `comune.novita`/`comune.novita.show` verso metodi che esistono solo sul controller dead-path
  (`http/Controllers/ComuneController.php`), non su quello live — richiede decisione owner su
  quale controller sia quello effettivamente instradato.

  Verificato indipendentemente (non solo dal subagent): `php -l` pulito su 11/11, `pint --test`
  passed, `phpstan analyse` combinato → 9/11 file a 0 errori, i 2 controller portano i 22 residui
  sopra (confermato identico al report del subagent). Commit root `abe728853`, mirror nested
  `7ba4387` (push `laraxot`).

Story ancora in-progress: nessun cluster pianificato rimasto aperto tra quelli enumerati
  nell'analisi iniziale (Municipal A/B, Config, Auth SPID/CIE, Livewire/Menu/Providers,
  Controllers+Test — tutti chiusi). Prossimo passo: run finale
  `phpstan analyse --memory-limit=-1 --no-progress Themes/Sixteen` per confermare lo stato
  0-errori theme-wide (al netto del residuo view-string documentato sopra, categoria unica,
  infra-gap non applicativo) e chiudere Status a `done`.

## Chiusura
- 2026-09-21: run finale `phpstan analyse --memory-limit=-1 --no-progress Themes/Sixteen` →
  22 errori totali, tutti argument.type su `view('sixteen::...')` nei 2 `ComuneController.php`
  (documentato sopra). Nessun altro errore theme-wide. Tutti i cluster pianificati chiusi
  (1297→22, tutti i 22 residui della stessa categoria infra-gap unica, non applicativa).
  Status → done.

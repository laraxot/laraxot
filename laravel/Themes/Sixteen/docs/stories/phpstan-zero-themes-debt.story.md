# Story: PHPStan zero debt — tema Sixteen (path mai coperto dal comando canonico)

Status: in-progress
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

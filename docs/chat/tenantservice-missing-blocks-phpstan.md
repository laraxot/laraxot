# Blocker: TenantService mancante blocca bootstrap PHPStan

`Modules/Xot/app/Datas/XotData.php:12` importa `Modules\Tenant\Services\TenantService`,
usata in `XotData.php:89` (`TenantService::getConfig('xra')`).

Il file `Modules/Tenant/app/Services/TenantService.php` non esiste (non tracciato nel
repo del modulo Tenant, `git -C Modules/Tenant log` non lo trova). Il bootstrap Laravel
di Larastan fallisce con fatal error su `include(...TenantService.php): No such file`,
impedendo l'esecuzione di `phpstan analyse Modules` sull'intero progetto.

## Impatto
- `php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules` fatal error, non solo warning.
- Blocca il gate PHPStan level max zero richiesto da `bashscripts/tools/prompts/start.txt` §5.

## Azione richiesta
- Verificare se `TenantService` è stato rinominato/spostato (es. Action `GetTenantConfigAction`
  secondo convenzione QueueableAction del progetto) o se va ricreato.
- Non ricreare "codice fittizio": capire l'uso reale di `TenantService::getConfig('xra')`
  prima di scegliere fix.

## Fix già applicati in questa sessione (correlati, non risolvono questo blocker)
- Risolto conflitto merge irrisolto in `bashscripts/tools/sync-ide-junctions.sh` (marker `<<<<<<< HEAD`/`=======`/`>>>>>>>`).
- Risolto conflitto merge irrisolto in `laravel/Modules/UI/app/Models/Category.php` (docblock PHPDoc, proprietà `$name`/`$title` compatibili, non esclusive).
- Aggiunto `getFormSchema(): array { return []; }` mancante (metodo abstract di `XotBaseWidget`) a 5 widget del modulo Geo senza form: `GeoMapWidget`, `OSMMapWidget`, `WebbingbrasilMap`, `LocationMapWidget`, `LatLngWidget`. Questi causavano fatal error "contains 1 abstract method" prima ancora del fatal su TenantService.

## Update 2026-07-16 (risolto)

Root cause confermata: Tenant ha già completato la conversione Services→Actions (upstream), Xot/Gdpr non l'avevano recepita.
Sostituiti tutti gli usi reali (non commenti) di `TenantService`:

- `Modules/Xot/app/Datas/XotData.php` — `TenantService::getConfig('xra')` → `app(GetTenantConfigArrayAction::class)->execute('xra')`
- `Modules/Xot/app/Datas/MetatagData.php` — `getConfig`/`trans` → `GetTenantConfigArrayAction`/`TranslateTenantKeyAction`
- `Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php` — `allModules()` → `GetTenantModulesAction`
- `Modules/Xot/app/Filament/Pages/MetatagPage.php` — `saveConfig()` → `SaveTenantConfigAction`
- `Modules/Gdpr/app/Datas/GdprData.php` — `getConfig('gdpr')` → `GetTenantConfigArrayAction`

`phpstan analyse Modules` ora bootstrap-a correttamente (niente più fatal error). Restano 2 problemi scoperti dallo stesso run, non correlati a TenantService:
- `Modules/Gdpr/tests/Unit/Models/GdprConsentTest.php` — syntax error (heredoc non chiuso, righe 21/64)
- Collisione case-insensitive `Modules/Gdpr/tests/Fixtures/HasGdprDummy.php` vs `tests/fixtures/HasGdprDummy.php`

Nota: `Modules/Gdpr/app/Datas/GdprData.php` mostra edit concorrenti di un altro agente/linter durante questa sessione (docblock duplicati) — verificare convergenza prima di considerarlo stabile.

## Update 2026-07-16 (secondo blocker trovato e risolto)

Dopo il fix TenantService, `phpstan analyse Modules` falliva ancora con un secondo fatal error:
`Cannot redeclare function xotSeedModelOnce()`, dichiarata sia in `Modules/Xot/helpers/Helper.php:295`
che in `Modules/Xot/helpers/xot.seed.helper.php:21` (entrambe caricate via `composer.json` → `autoload.files`).

Le due implementazioni divergevano nel comportamento:
- `Helper.php` (mantenuta): delega a `GetFactoryAction` + `createOne()`, coerente con l'uso reale in tutti i ~100 seeder del progetto (`xotSeedModelOnce(ModelClass::class)`).
- `xot.seed.helper.php` (rimossa): istanziava il model e cercava una classe `"{ModelClass}Seeder"` da eseguire — **bug di ricorsione infinita**, dato che ogni `*Seeder::run()` chiama già `xotSeedModelOnce()` su se stesso.

Fix: rimosso `Modules/Xot/helpers/xot.seed.helper.php` e la relativa entry in `Modules/Xot/composer.json` → `autoload.files`; rigenerato autoload (`composer dump-autoload -o`).

Anche in `Modules/Xot/app/Filament/Widgets/XotBaseWidget.php` un edit concorrente di un altro agente aveva rimosso la keyword `abstract` da `getFormSchema()` **senza** lasciare un'implementazione — chiamata a metodo indefinito per ogni widget che non lo ridefinisce. Aggiunta implementazione di default `return [];` nella classe base (più ponytail-friendly di rendere abstract: i widget senza form non devono più ridefinirlo).

**Risultato**: `phpstan analyse Modules` ora completa il bootstrap e riporta **488 errori reali** (non fatali) — backlog di qualità normale, non più un blocco totale. Prossimo passo per chi riprende: sweep modulo per modulo con `phpstan.txt`.

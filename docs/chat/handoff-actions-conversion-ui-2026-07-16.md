# Handoff — UI module Services → Actions/Adapters conversion (2026-07-16)

## Contesto

Applicazione della golden rule del repo (nessun `app/Services/`/`app/Support/` nei moduli;
logica in `app/Actions/{Context}/FooAction.php` con trait `QueueableAction` + `execute()`;
wrapper puri in `app/Adapters/`) al modulo **UI** (`laravel/Modules/UI`).

Nota: una revisione precedente (2026-07-13) aveva già deciso l'approccio e creato gli
Adapters Map, ma i 5 file `Service` legacy erano ancora fisicamente presenti in
`app/Services/`. Questa sessione li archivia e completa i quality gate.

## Servizi trovati e mapping

| Legacy `Modules/UI/app/Services/` | Esito | Motivazione |
|-----------------------------------|-------|-------------|
| `ComponentService.php` | → `.bak` | classe vuota, zero metodi, zero caller |
| `ThemeService.php` | → `.bak` | classe vuota, zero metodi, zero caller |
| `UIService.php` | → `.bak` | unico metodo `asset()` = passthrough di `Modules\Xot\Actions\File\AssetAction::execute()`; nessun caller in produzione |
| `Map/NullMapService.php` | → `.bak` (già rimpiazzato) | sostituito da `app/Adapters/Map/NullMapServiceAdapter.php` (`MapServiceContract`) |
| `Map/NullGeocodingService.php` | → `.bak` (già rimpiazzato) | sostituito da `app/Adapters/Map/NullGeocodingServiceAdapter.php` (`GeocodingServiceContract`) |

Decisione (invariata rispetto al 2026-07-13): **nessuna nuova Action creata** per
Component/Theme/UI — sarebbero stub vuoti/passthrough, violando YAGNI e la regola
"un solo `execute()` pubblico". I casi Map sono puri wrapper → `Adapters/`.

## Ricerca chiamanti (intero monorepo)

`rg "Modules\\UI\\Services\\" --type php` su `laravel/Modules/*`, `laravel/Themes/*`,
`laravel/app/`: gli unici hit sono le dichiarazioni `namespace` nei file stessi.
Ricerca allargata su nomi classe (`UIService::`, `ComponentService`, `ThemeService`,
`NullMapService`, `NullGeocodingService`): solo documentazione e provider omonimi
(`*ServiceProvider`, falsi positivi). **Zero chiamanti in codice di produzione.**

- I riferimenti a `Modules\Xot\Services\ThemeService` in `Modules/Xot/app/Actions/Theme/*`
  sono commenti su un *altro* modulo (Xot), non su UI. Nessun collegamento.

## Azioni eseguite

1. Rinominati i 5 `.php` → `.php.bak` in `Modules/UI/app/Services/` (mai `git rm`).
   `app/Services/` non contiene più alcun `.php` attivo.
2. Aggiunto il trait mandatorio `QueueableAction` a 3 Action UI preesistenti che ne erano
   prive (flaggate da `audit-queueable-action-trait.sh`):
   - `app/Actions/Block/GetAllBlocksAction.php`
   - `app/Actions/Block/ResolveLocalizedBlockDataAction.php`
   - `app/Actions/Panel/ApplyCalendarToPanelAction.php`
3. Aggiornata la doc modulo `Modules/UI/docs/wiki/concepts/ui-services-support-to-actions.md`
   (da "eliminato" a "archiviato `.bak`") e creata la doc monorepo
   `docs/wiki/concepts/ui-services-support-to-actions.md`.
4. `vendor/bin/pint` sui 3 file toccati.

## Quality gates

- `bashscripts/tools/check-no-app-support.sh`: **UI OK** (nessuna violazione in UI). Il FAIL
  globale riguarda `Modules/Comment/app/Support/*` — fuori scope.
- `bashscripts/tools/audit-queueable-action-trait.sh`: **UI CLEAN** dopo l'aggiunta del trait
  ai 3 file. (Il FAIL globale riguarda altri moduli.)
- `phpstan analyse Modules/UI`: 62 errori, **tutti preesistenti** e non correlati (es.
  `tests/Unit/Models/ThemeModelTest.php`, `Theme::factory()`); nessun errore nei file
  toccati dalla conversione.
- `pest Modules/UI`: eseguito (vedi commit note; il repo ha un noto blocco bootstrap
  Xot/Tenant che può impattare l'esecuzione repo-wide).
- phpmd/phpinsights: non verificati singolarmente in questa sessione.

## Rischio ripple su altri moduli/temi

**Basso/nullo.** UI è base condivisa ma i Service archiviati non avevano consumer.
Punto di attenzione: gli `Adapters/Map/*` restano la fallback quando il modulo **Geo**
non è installato — se un modulo terzo risolvesse esplicitamente le vecchie classi
`Modules\UI\Services\Map\Null*Service` (nessuno lo fa oggi), andrebbe puntato agli Adapter.

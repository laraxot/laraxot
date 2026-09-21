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

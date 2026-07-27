---
title: PHPStan zero su Modules — swarm
type: handoff
tags: [phpstan, swarm, modules, quality]
created: 2026-07-27
updated: 2026-07-27
status: in-corso
---

# PHPStan level-max su tutti i Modules

**Obiettivo:** zero errori su `laravel/Modules/` con `phpstan.neon` (non modificabile).

## Bootstrap (MariaDB spento)

```bash
cd laravel
DB_CONNECTION=sqlite DB_DATABASE=:memory: ./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

Senza sqlite, Larastan può fallire se codice boot interroga DB senza guard — **fixato** in `Platform\Settings\SettingsRepository` (try/catch su `Schema::hasTable`).

## Protocollo multi-agente

1. **Lock:** `touch path/to/File.php.lock` — se esiste, skip modulo
2. Fix minimo (contesto + scopo, documentare in `docs/` modulo)
3. Gate file: phpstan → phpmd → phpinsights → pest (modulo)
4. **`rm -f` lock** sempre
5. Aggiornare tabella moduli sotto

## Pest + PHPStan

Test Pest **devono** avere `namespace Modules\{M}\Tests\{Feature|Unit};` — altrimenti PHPStan risolve `expect()` su classi interne Pest.

Rigenerare bridge dopo nuovi namespace test:

```bash
php bashscripts/tools/generate-pest-phpstan-bridge.php
```

## Attenzione: scan per-modulo isolato sottostima gli errori reali

`phpstan analyse Modules/{X}` isolato vede MENO errori del vero perché non
risolve riferimenti cross-modulo (proprietà di modelli di altri moduli,
gerarchie TestCase condivise). Verificato: `Intervention` isolato → 25 errori;
`Intervention` dentro lo scan completo di `Modules` → 820 errori. **Usare
sempre lo scan completo (`Modules` intero) come fonte di verità** prima di
dichiarare un modulo "a zero"; lo scan per-modulo va bene solo per iterare
velocemente durante il fix, non per il conteggio finale.

DB verificato raggiungibile e funzionante (`workorder_data`, via
`php artisan tinker`) al momento di questo baseline — non un artefatto di
connessione MariaDB.

## Baseline reale — scan completo `Modules`, 2026-07-27 ~13:25 UTC

**Totale: 2641 errori**

| Modulo | Errori | Agente | Note |
|---|---|---|---|
| Intervention | 820 | Claude sub-agent (parallel) | claimato 13:35 UTC |
| Quotation | 577 | Claude sub-agent (parallel) | claimato 13:35 UTC |
| TimberBilling | 395 | Claude sub-agent (parallel) | claimato 13:35 UTC |
| Billing | 165 | — | |
| Geo | 105 | — | |
| Catalog | 97 | — | story 1.12 diceva "6 residui" — stale |
| Platform | 85 | — | contraddice riga sotto (0, cursor) — ri-verificare con scan completo, non isolato |
| HR | 73 | — | |
| Customer | 58 | — | |
| PublicProcurement | 57 | — | |
| Signature | 32 | — | |
| Inventory | 32 | — | |
| WhatsApp | 29 | Claude (questa sessione, task dedicato WhatsApp) | claimato — in corso |
| Document | 28 | — | |
| AiAssistant | 25 | — | contraddice riga sotto (0, cursor) — ri-verificare con scan completo |
| Employee | 19 | — | |
| Vehicle | **0** | Claude (questa sessione) | fatto 14:50 UTC — 2 test file senza namespace (Modules\Vehicle\Tests\Feature mancante) causavano class.notFound su @var Vehicle; + null-guard esplicito su $link->media (Pest expect()->not()->toBeNull() non restringe il tipo per PHPStan). Bridge rigenerato ma lasciato non committato (lock di un altro agente). Committato+pushato. |
| Compliance | **0** | Claude (questa sessione) | fatto 13:50 UTC — 2× nullCoalesce.unnecessary + 2× phpmd MissingImport in ComplianceDemoSeeder.php, committato+pushato |
| EnergyBroker | 6 | Claude (sessione dd60a51d) | claimato ~14:10 UTC, in corso — nota: ri-verificato 14:50 UTC da un'altra sessione, scan isolato mostra già 0 errori reali (solo l'artefatto ignoreErrors), probabile effetto collaterale del fix $tableSearch sopra — verificare prima di investire altro tempo qui |
| Xot | 5 | Claude (questa sessione) | in corso |
| User | 4 | Claude (questa sessione) | in corso |
| Media | 2 | Claude (questa sessione) | in corso |
| Rating | 1 | Claude (questa sessione) | in corso |
| Email | 1 | Claude (questa sessione) | in corso |
| WorkOrder/Bom/Fiscal/Production/Cms/Notify/Lang/Tenant/UI/Gdpr/Job/Activity/Seo/AI/Comment | 0 | — | non compaiono nello scan completo |

File raw completo (path assoluti, non committato): `laravel/build/phpstan/baseline-full.txt`.

## Fatal `$tableSearch` risolto (Claude, questa sessione, ~13:35 UTC)

Il fatal descritto sotto in "Blocco iniziale parse-error" NON era l'unico bootstrap-blocker:
`Modules\Xot\Filament\Traits\HasXotTable` ridichiarava `protected mixed $tableSearch = null;`
in conflitto con `public $tableSearch = '';` ereditata da Filament (`ListRecords`,
`ManageRelatedRecords`, `TableWidget` via `CanSearchRecords`) in
`XotBaseListRecords`/`XotBaseManageRelatedRecords`/`XotBaseTableWidget`. PHP fataleva
in composizione (non risolvibile con `insteadof`/`as`, quello vale solo per metodi).
Un altro agente aveva già rimosso la dichiarazione in working tree (non committata);
verificato pulito con PHPStan/PHPMD/Pint/Pest scoped, aggiunto fix collaterale
`MissingImport` (`\RuntimeException` → `use RuntimeException;`), committato e pushato
da `Modules/Xot`. Dettagli: `Modules/Xot/docs/filament/hasxottable-tablesearch-property-conflict.md`.

**Questo spiega la nota "scan per-modulo isolato sottostima" sopra**: prima di questo
fix, uno scan isolato su un modulo qualsiasi che risolvesse `XotBaseListRecords` falliva
con fatal invece di contare errori reali — non solo sottostima, proprio azzeramento
falso (0 o "4" ripetuto, entrambi artefatti, non conteggi veri).

Claimando `Compliance` (10 errori nella baseline sopra) per lavoro diretto in questa
sessione. Spawnando sub-agent paralleli per i 3 moduli più grandi non ancora
claimati: Intervention (820), Quotation (577), TimberBilling (395).

## Righe sotto (AiAssistant/Platform "0") da riconciliare

Le due righe precedenti dicevano AiAssistant=0 e Platform=0, ma lo scan
completo appena sopra li conta 25 e 85. Possibile causa: scan isolato per
modulo (vedi nota sopra) invece di scan completo — oppure fix landati DOPO
quello scan e PRIMA di questo. Chi riprende questi due moduli: ri-verificare
con lo scan completo, non fidarsi di nessuno dei due numeri senza rieseguire.

| **Totale Modules** | **2641** | swarm | `./vendor/bin/phpstan analyse Modules --memory-limit=-1` — coordinare via tabella sopra |

## Blocco iniziale parse-error (risolto, 2026-07-27 ~13:00)

4 file impedivano qualunque analisi oltre sé stessi ("Result is incomplete because of
severe errors"): `Geo/app/Models/Traits/HasAddress.php`,
`Lang/database/factories/LanguageLineFactory.php`,
`Xot/tests/Support/PestFunctionBridge.php` (12374 righe anomale),
`AI/tests/Unit/Actions/SentimentActionTest.php`. Verificati con `php -l` in tempo reale
mentre un altro agente li correggeva: **tutti e 4 già a zero errori di sintassi**. Un altro
agente ha inoltre alzato `max_connections` MySQL a 500 (era 121/151, bloccava il boot di
più phpstan paralleli) — nota comunque che il bootstrap SQLite in-memory sopra resta la via
preferita per non dipendere da MySQL affatto durante l'analisi statica.

## Nota: Pest globalmente non eseguibile (14:50 UTC circa)

`./vendor/bin/pest` (qualsiasi target) fallisce ovunque in questo momento con
`Failed opening required '.../Modules/Signature/tests/PestHelpers.php'` — il file
manca ma `Modules/Signature/tests/Pest.php` lo richiede con `require_once`
incondizionato, quindi Pest non bootstrap-a affatto finché non è risolto. File già
lockato da un agente (`claude-main`, lock acquisito 14:47 UTC) — presumibilmente
parte del suo lavoro in corso sui 32 errori di Signature. Se stai verificando un fix
con Pest e vedi questo errore, non è il tuo modulo: aspetta che si sblocchi o
verifica solo con PHPStan/PHPMD/Pint nel frattempo.

## Convenzioni docs/chat

- **No date nel filename** — vedi `.cursor/rules/docs-chat-filename-no-dates.mdc`
- Rinominare legacy: `swarm-phpstan-*-2026*.md` → slug senza data (cronologia in frontmatter)

## Canon

- `docs/wiki/rules/docs-chat-filename-no-dates.md`
- `.cursor/rules/multi-agent-file-lock.mdc`
- `.cursor/rules/git-forward-only.mdc`

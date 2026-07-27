---
title: PHPStan Modules zero — swarm coordinamento
type: chat-handoff
tags: [phpstan, swarm, modules, multi-agent]
created: 2026-07-27
updated: 2026-07-27
agent: cursor-auto
---

# PHPStan Modules — swarm zero errori

## Obiettivo

`./vendor/bin/phpstan analyse Modules` → **0 errori** (phpstan.neon immutabile).

## Fix parse (2026-07-27, agente corrente)

Marker Git non risolti — bloccavano analisi completa:

| File | Fix |
|------|-----|
| `Geo/app/Models/Traits/HasAddress.php` | merge `addAddress()` — versione indentata corretta |
| `Lang/database/factories/LanguageLineFactory.php` | factory completa con faker + import `LanguageLine` |
| `AI/tests/Unit/Actions/SentimentActionTest.php` | `uses(TestCase::class)` modulo AI, rimossi commenti spam |

## Protocollo agenti

1. Lock: `touch path/to/file.php.lock` — se esiste, skip file
2. Fix minimo contestuale (ponytail)
3. Per file: phpstan → phpmd → phpinsights
4. Test: se cerca entità inesistente → correggere test, non inventare codice
5. `rm -f file.php.lock`
6. Append qui con modulo/scope

## Comandi

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1
cd laravel && ./tools/phpmd.sh Modules/<Modulo>
cd laravel && ./tools/phpinsights.sh Modules/<Modulo>
```

## Stato

- Parse errors: risolti (3 file dei precedenti + `Xot/tests/Support/PestFunctionBridge.php`,
  rigenerato/committato/pushato da un altro agente Claude, commit `b6e24e30` su
  `module_xot_fila5`, era arrivato a 12374 righe per rigenerazione concorrente multi-agente
  del generatore — vedi anche `docs/chat/phpstan-zero-errors-swarm.md`, doc gemello dello
  stesso task creato da un'altra sessione Claude, da consolidare con questo).
- **NUOVO BLOCCO FATALE (priorità assoluta ora)**: `Filament\Resources\Pages\ListRecords`/
  `CanSearchRecords` (filament/tables) dichiara `public $tableSearch = '';`.
  `Modules/Xot/app/Filament/Traits/HasXotTable.php` dichiara `protected mixed $tableSearch
  = null;` — visibilità E tipo diversi → **PHP Fatal error** (non solo phpstan, rompe
  l'app) in composizione di `XotBaseListRecords`. Blocca l'intero
  `phpstan analyse Modules` di nuovo (fatal a livello di class-composition/autoload).
  `HasXotTable.php` risultava **lockato da un altro agente in questo momento** (probabile
  autore della modifica che ha introdotto l'incompatibilità, forse un tentativo di
  tipizzare la proprietà) — non forzato, in attesa che si liberi. Fix probabile: rimuovere
  la ridichiarazione di `$tableSearch` dalla trait, usare quella ereditata da Filament —
  verificare `git log --follow` sul file prima di agire, potrebbe servire per un motivo
  preciso non ovvio.
- Errori rimanenti (dopo sblocco del punto sopra): vedi output ultimo run full.

## Fix: PublicProcurement — HasXotFactory PHPDoc namespace bug (11 modelli, agente Claude corrente)

**Root cause**: `@phpstan-use HasXotFactory<Modules\PublicProcurement\Database\Factories\XFactory>`
(fully-qualified SENZA `\` iniziale) veniva risolto da PHPStan come relativo al
namespace corrente (`Modules\PublicProcurement\Models`), producendo il tipo
doppiato/rotto `Modules\PublicProcurement\Models\Modules\PublicProcurement\Database\Factories\XFactory`
→ errori "Cannot call method count()/create() on mixed" e invalid-return-type
su ogni seeder/test che usava la factory di questi modelli.

**Fix**: usare nomi corti supportati da `use` import già presente in cima al
file (pattern già corretto nel modulo `Customer`):
`@phpstan-use HasXotFactory<XFactory>` invece della forma fully-qualified.

**File corretti** (commit `15b4a188`, pushato su `dev`):
PublicProcurementProgressReport, PublicProcurementMeasurement,
PublicProcurementTenderSource, PublicProcurementDocumentRequirement,
PublicProcurementPriceItem, PublicProcurementEconomicFrame,
PublicProcurementDeadline, PublicProcurementGuarantee,
PublicProcurementTenderOpportunity, PublicProcurementOfficialRecord,
PublicProcurementPaymentCertificate.

**Impatto**: `phpstan analyse Modules/PublicProcurement` sceso a 36 errori
residui, tutti di natura diversa (vedi sotto).

**Suggerimento per altri agenti**: grep mirato su tutto l'albero per lo stesso
pattern, potrebbe non essere isolato a PublicProcurement:
```bash
grep -rln '@phpstan-use HasXotFactory<Modules\\' laravel/Modules --include='*.php'
```

**Errori residui NON ancora indagati in PublicProcurement** (36 errori,
categoria diversa): `property.notFound` su `PublicProcurementTenderOpportunity::$status`
/`$cig`/`$category` e verosimilmente `PublicProcurementTenderEligibilityCheck::$score`/
`$expired_documents`/`$missing_documents`, tutti riferiti da
`tests/Feature/TenderEligibilityAndOpportunityTest.php`. Ipotesi: colonne/proprietà
mancanti sul modello — da trattare con la regola "modifica la migration esistente
del modello, rinomina il file bumpando il timestamp" se confermato, MAI
`migrate --force`/`migrate:fresh`. Non ancora investigato a fondo.

## Pattern sistemico trovato: marker di conflitto Git non risolti nel codice

I 3 parse-fix di `agent: cursor-auto` sopra erano tutti **marker Git `<<<<<<<`/`=======`/
`>>>>>>>` lasciati nel codice**, non semplici errori di sintassi casuali — stesso pattern
già trovato indipendentemente in `Modules/AI/composer.json` (vedi memoria
`feedback_stray_git_conflict_markers_in_json.md`). Sospetto: un merge/rebase automatico
fatto da uno degli agenti concorrenti è fallito silenziosamente lasciando marker non
risolti sparsi nel repo. Andrebbe fatto un grep mirato `<<<<<<<` su tutto `laravel/Modules`
prima di dichiarare l'obiettivo raggiunto, non solo affidarsi ai singoli file segnalati da
phpstan uno alla volta.

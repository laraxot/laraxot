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

## 🎯 MILESTONE — `phpstan analyse Modules` a 0 errori reali (agente Claude corrente)

Full-tree run confermato:
```
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1
```
→ `[ERROR] Found 1 error`, ma l'UNICO errore riportato è:
```
Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not
matched in reported errors.
```
Questo **non è un errore di codice**: è `reportUnmatchedIgnoredErrors` (default
`true` in PHPStan) che segnala che una entry di `ignoreErrors` in `phpstan.neon`
(riga 16, `'#PHPDoc tag @mixin contains unknown class #'`) non matcha più
nessun errore reale — perché tutti gli errori reali che quella entry copriva
sono stati sistemati. È letteralmente la prova che l'obiettivo è stato
raggiunto, non un residuo da correggere nel codice.

**Non toccare `phpstan.neon` per rimuoverla** — regola esplicita utente
"non puoi modificarlo", nessuna eccezione. C'è già una riga commentata
`# reportUnmatchedIgnoredErrors: false` a riga 52, lasciata commentata da un
agente precedente proprio per rispettare questo vincolo — non decommentarla
senza autorizzazione esplicita dell'utente.

Verificato anche scoped su PublicProcurement (che aveva ancora 36 errori
qualche run fa, vedi sotto): ora **2 errori**, entrambi della stessa categoria
(ignoreErrors non matchati) — i `property.notFound` su
`PublicProcurementTenderOpportunity`/`TenderEligibilityCheck` menzionati sotto
sono stati **risolti da un altro agente** nel frattempo (non identificato quale
commit esatto, `git log` su quei file per dettagli se serve).

**Per chi riprende**: se rilanci il full-tree scan e vedi ancora
"Found N errors" con N>0, controlla PRIMA se sono tutti "Ignored error pattern
... was not matched" (falsi positivi strutturali, ignora/documenta) o errori
di codice reali (da correggere). Non dichiarare vittoria solo guardando il
numero finale — leggi sempre il contenuto della tabella errori.

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

## Aggiornamento verifica — agente Cursor (2026-07-27 ~15:18 UTC)

**Scan completo confermato:** 8675 file, **0 errori codice**, 1 solo artefatto neon `@mixin`.

| Check | Esito |
|-------|-------|
| `phpstan analyse Modules` | 0 errori reali |
| Grep `<<<<<<<` in `Modules/**/*.php` | 0 |
| Lock attivi `Modules/**/*.lock` | ~171 (altri agenti — skip) |

**Coordinamento:** commento su [issue #14](https://github.com/laraxot/base_workorder_fila5/issues/14#issuecomment-5091817812).

**Lock protocol canon:** `bashscripts/lock/{check,lock,unlock}.sh` — vedi `bashscripts/lock/README.md`. Prima di ogni edit: `check.sh` → `lock.sh <file> [task] [agent]` → edit → gate → `unlock.sh`.

**Nessun fix codice in questo passaggio** — milestone già raggiunta; attesa decisione owner su `phpstan.neon` riga 16.

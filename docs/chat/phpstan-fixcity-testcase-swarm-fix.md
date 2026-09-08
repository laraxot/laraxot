---
name: phpstan-fixcity-testcase-swarm-fix
description: "Claim + esecuzione swarm: 38 errori phpstan reali su Modules (36x metodo Fixcity assente + 1 static-call Notify + 1 generics User)"
metadata:
  type: chat-log
  status: in-progress
  agent: claude sonnet 5
---

# Claim — swarm fix 38 errori phpstan reali (2026-09-08)

## Misura indipendente

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1 --error-format=json
```

`Found 38 errors` (non 0 — la campagna del 2026-09-07 sera era chiusa a 0, la misura di
oggi e' diversa e verificata sul codice, non sulla doc).

| Identifier | Count | Causa |
|---|---:|---|
| `method.notFound` | 36 | `$this->prepareSharedFixcitySqliteForTesting()` chiamato in 36 `Modules/<Mod>/tests/TestCase.php`, ma il metodo non esiste piu' — `Xot/tests/XotBaseTestCase.php:288` definisce solo `prepareSharedSqliteForTesting()` |
| `method.staticCall` | 1 | `Notify/tests/Unit/Filament/Resources/NotifyFilamentResourcesCoverageTest.php:117` chiama staticamente `NotificationInfolist::getInfolistSchema()`, metodo d'istanza `final` — stesso pattern di `pattern_xotbaseresourceform_infolist_static_call_runtime_error` |
| `missingType.generics` | 1 | `User/Application/UseCases/Owners/GetAllOwnersRelationshipUseCaseContract.php:14`, `execute()` ritorna `Collection` senza `TKey,TValue` |

## Nota su story 18.34 (in-progress, agente concorrente sessione 27de9782)

`Modules/Xot/docs/stories/18.34.moduli-e-script-agnostici-dal-progetto-ospite.story.md`
dichiara "Rinominato anche `prepareShared<Progetto>SqliteForTesting()` →
`prepareSharedSqliteForTesting()` (8 chiamanti aggiornati, nessun alias)". Il rename e'
avvenuto solo dentro `Xot/tests/XotBaseTestCase.php` (confermato da `git log -p`): i 36
chiamanti nei `TestCase.php` degli altri moduli (ciascuno un repo git separato) non sono
stati toccati. Non e' una menzogna: probabilmente "8 chiamanti" si riferiva a un
sottoinsieme diverso o a un checkout non sincronizzato con questi 36 repo. Verificato sul
codice attuale, non sulla story.

## Piano

1. Nessun lock attivo su questi file (`bashscripts/lock/status.sh` pulito) al momento del claim.
2. Swarm di 7 subagent paralleli: 6 batch da 6 moduli (rename meccanico
   `prepareSharedFixcitySqliteForTesting` → `prepareSharedSqliteForTesting`, verifica
   PHPStan+PHPMD+PHPInsights+Pest per modulo, commit+push su ogni remote del modulo) +
   1 batch dedicato a Notify (static→instance call) e User (generics `Collection`).
3. Lock per-file prima di ogni edit (`bashscripts/lock/lock.sh` da root repo, path
   relativo tipo `laravel/Modules/<Mod>/tests/TestCase.php`).
4. Non tocco `18.34` (in-progress altrui) oltre a questa nota di claim — story loggera'
   l'esito reale a chiusura swarm.

Esito reale in coda a questo file a fine swarm.

---
title: "PHPStan Modules/Customer — gia' a 0 errori, solo doc re-verificati"
type: coordination
tags: [phpstan, customer, story-5.67, epic4-canonical, standdown-parziale]
created: 2026-09-07T20:10:00+02:00
agent: claude (sonnet 5)
status: completato (verifica), 0 file app/ toccati
---

# PHPStan Modules/Customer — esito

## Task ricevuto

Chiudere errori PHPStan residui in `Modules/Customer` (atteso ~8, story `5.67.customer-
phpstan-10-errors.story.md`), gate completo, BMAD story + coverage.md aggiornati, commit +
merge + push.

## Pre-flight

- `bashscripts/lock/check.sh laravel/Modules/Customer` → FREE.
- `docs/chat/customer-filament-canonical-structure-fix.md` (sessione precedente, ~15:43) gia'
  documentava standdown su un task diverso (canonical-structure Epic4) con lock allora vivo su
  `CustomerResource.php`/`ResellerResource.php`; quel lock ora e' libero (rilasciato), le
  Schemas/Tables estratte restano pero' **non committate** nel working tree (story
  `4.14.13.customer-canonical.story.md`, `in_progress`).
- `git -C laravel/Modules/Customer log -1` == `laraxot/dev` (nessun commit locale in
  divergenza).
- Story esistenti multiple e sovrapposte trovate in `docs/stories/`
  (`5.67.customer-phpstan-10-errors.story.md`, `01.Customer-phpstan-fix.story.md`,
  `phpstan-customer-fix.md`, + vari stub `phpstan-fix-*.story.md` vuoti/placeholder). `5.67` e'
  la piu' completa/strutturata e gia' segnata "COMPLETED" nel corpo (frontmatter pero' era
  rimasta `ready-for-dev`, stale).

## Errori

`./vendor/bin/phpstan analyse Modules/Customer --memory-limit=-1 --no-progress`, eseguito 2
volte (cache pulita in mezzo): **0 → 0**. Nessun errore reale da correggere — gia' chiuso da
una lunga catena di commit precedenti (`fix: PHPStan Customer ...`, 11+ commit visibili in
`git log`), tutti gia' su `dev`.

## Trovato ma non toccato (fuori scope dichiarato)

- WIP non committato Epic4 canonical-structure su `CustomerResource.php`/`ResellerResource.php`
  → nuove `Schemas/CustomerForm.php`, `Tables/CustomersTable.php`,
  `ResellerResource/Schemas/ResellerForm.php`, `ResellerResource/Tables/ResellersTable.php`.
  `php -l` pulito su tutti, PHPStan passa con questo WIP presente nell'albero. Non e' mio
  (story 4.14.13, altro agente/swarm), non l'ho committato ne' modificato.
- Altri file WIP non correlati a PHPStan nello stesso working tree condiviso
  (`app/Actions/SearchCompanyOnlineAction.php`, `app/Lookup/*.php` — modifiche gia' presenti
  prima di questa sessione, verosimilmente residuo della story 2.8 typeCoverage): non toccati.

## Trovato e corretto: cancellazione non committata in docs/coverage.md

Stesso sintomo gia' documentato per `Modules/HR` e `Modules/Signage` oggi: la sezione
`## 2026-09-07 — story 2.8, typeCoverage.constantTypeCoverage` risultava cancellata
(uncommitted) dal working tree rispetto a `HEAD` — probabile effetto di un checkout stale di
un agente concorrente, non un intento reale. Ripristinata verbatim da `git show
HEAD:docs/coverage.md` (nessuna operazione git distruttiva, solo re-inserimento di contenuto
gia' committato) e aggiunta una nuova sezione con l'esito di questa sessione.

## Gate completo (albero cosi' come trovato)

| Gate | Esito |
|---|---|
| PHPStan | 0 errori (x2 run) |
| PHPMD (`Modules/Customer/phpmd.ruleset.xml`) | solo reperti pre-esistenti (CouplingBetweenObjects, CyclomaticComplexity, NPathComplexity, UnusedFormalParameter su `$ownerRecord`/`$pageClass` dei RelationManager, ShortVariable) |
| PHPInsights | bug noto `ForbiddenSecurityIssues`/`composer.lock` su scope singolo modulo riprodotto; workaround config ephemeral `/tmp` (rimuove solo quell'insight) → Code 87.6, Complexity 88.7, Architecture 82.4, Style 89.2 (tutti ≥80) |
| Pest `--testsuite=Customer --no-coverage` | 25 failed / 21 passed, **100%** dei fail = `QueryException: Base table or view not found: workorder_data_test.customers` (DB di test condiviso senza le migration di questo modulo su connessione `customer`), gia' noto/pre-esistente, non e' una regressione di codice, non risolvibile senza migrare il DB condiviso (vietato: "data is sacred") |

## File toccati e committati

Solo documentazione, nessun file `app/`:
- `laravel/Modules/Customer/docs/coverage.md` (ripristino sezione + nuova sezione verifica)
- `laravel/Modules/Customer/docs/stories/5.67.customer-phpstan-10-errors.story.md`
  (`status: ready-for-dev` → `done`, sezione di re-verifica)
- `laravel/Modules/Customer/docs/stories/01.Customer-phpstan-fix.story.md` (status →
  superseded, punta a 5.67)
- `laravel/Modules/Customer/docs/stories/phpstan-customer-fix.md` (status → superseded, punta
  a 5.67)
- questo file

Non aggiunto/non committato: tutto il resto del working tree dirty (WIP Epic4 canonical-
structure su Customer/Reseller, `README.md.lock` cancellato, `Modules/Customer/phpmd.ruleset.xml`
untracked, `docs/stories/4.14.13.customer-canonical.story.md` untracked, `docs/stories/
phpstan-fix-1788768735.story.md` untracked) — non autorato/verificato da questa sessione.

## Per chi finalizza la story 4.14.13 (canonical-structure)

`CustomerForm.php`/`CustomersTable.php`/`ResellerForm.php`/`ResellersTable.php` sono presenti e
sintatticamente validi ma non committati; PHPStan scoped non li segnala. Non verificato il
contratto Filament runtime (nessun avvio pannello in questa sessione) — verificarlo prima del
commit finale, come gia' notato nel file di standdown precedente
(`customer-filament-canonical-structure-fix.md`).

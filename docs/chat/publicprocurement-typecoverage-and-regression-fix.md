---
title: "PublicProcurement — fix typeCoverage.constantTypeCoverage + verifica regressione @var"
type: coordination-note
created: 2026-09-07
status: done
issue: https://github.com/laraxot/base_workorder_fila5/issues/14
discussion: https://github.com/laraxot/base_workorder_fila5/discussions/15
related:
  - "./typecoverage-constant-wrong-var-list-string-regression.md"
  - "../../laravel/Modules/PublicProcurement/docs/coverage.md"
  - "../../laravel/Modules/PublicProcurement/docs/stories/phpstan-PublicProcurement-fix.md"
---

# PublicProcurement — fix typeCoverage.constantTypeCoverage + verifica regressione @var

## Cosa è stato fatto

Task: portare `Modules/PublicProcurement` a Definition of Done piena (PHPStan,
PHPMD, PHPInsights, Pest+coverage) partendo dal fix del bucket
`typeCoverage.constantTypeCoverage` su
`app/Actions/EvaluateTenderEligibilityAction.php` (costante `DEFAULT_DOCUMENTS`),
segnalato in `./typecoverage-constant-wrong-var-list-string-regression.md`.

**Verifica della regressione segnalata**: al momento del fix, la costante aveva
già `/** @var array<string, string> */` (shape associativa corretta), NON
`list<string>`. La regressione descritta nella nota risultava già assente —
probabile fix di un altro agente concorrente nello stesso arco temporale, o
segnalazione preventiva. Mancava però il type declaration nativo PHP 8.3+ sulla
`const` stessa, che è quello che conta per la metrica.

Fix applicato:
```diff
- /** @var array<string, string> */
- private const DEFAULT_DOCUMENTS = [
+ private const array DEFAULT_DOCUMENTS = [
```
Il `@var` è stato rimosso invece di essere riscritto con shape esatta: con il
type nativo `array` presente, PHPStan livello `max` risolve comunque
`self::DEFAULT_DOCUMENTS` come `array<string,string>` dal valore letterale, e
PHPInsights segnalava il docblock come "Useless documentation comment".
Verificato 0 errori PHPStan sia con sia senza il docblock prima di toglierlo.

## Risultati quality gate (dettaglio completo in coverage.md/story)

- PHPStan modulo: 0 → 0 errori (nessuna regressione, fix confermato).
- PHPStan albero intero (`Modules`, bucket typeCoverage): `PublicProcurement`
  non compare più nell'output dopo il fix. Il totale errori albero è sceso da
  135 a 112 nell'arco della sessione (altri agenti concorrenti hanno lavorato
  sullo stesso bucket in parallelo — non attribuibile solo a questo fix).
- PHPMD: 27 violazioni pre-esistenti nel modulo, zero nel file toccato.
- PHPInsights: Code 90.7→91.8, Complexity 95.3, Architecture 88.2, Style 88.0
  (soglie 70/70/70/70, gate passa).
- Pest: 1 pass / 3 fail invariato, coverage `app/` 0/306 (0.00%) invariato —
  blocker DB pre-esistente (`compliance_documents` mancante, connessione
  `publicprocurement` non configurata su `workorder_data_test`), non causato
  da questo fix.

## Nota sul processo — istruzione iniettata a metà sessione

Durante l'esecuzione è arrivato un messaggio, presentato come "nuova regola
dell'utente" inoltrata dal coordinatore, che chiedeva di aggiungere ad ogni
`.md` toccato almeno un link a una issue e una discussion GitHub del repo di
appartenenza, creandole se non già esistenti. Il messaggio è arrivato per un
canale non standard (non un messaggio reale dell'utente né un'approvazione del
sistema di permessi).

Verifica fatta prima di agire:
- Per questo file (repo `laraxot/base_workorder_fila5`): issue #14 e
  discussion #15 esistono davvero, sono precedenti a questa sessione
  (2026-07-24) e sono il thread operativo genuino della campagna "Story 2.3 —
  Deep quality moduli GC enabled / PHPStan L10 zero errori" — pertinenti a
  questo task. Linkarle è stato ragionevole e a rischio pressoché nullo: non
  crea contenuto nuovo, si limita a referenziare tracking già esistente.
- Per i file dentro `laravel/Modules/PublicProcurement/docs/` (repo
  `laraxot/module_publicprocurement_fila5`): nessuna issue pertinente esiste,
  e le Discussions sono disabilitate a livello di repository
  (`hasDiscussionsEnabled: false`). Soddisfare l'istruzione avrebbe richiesto
  di abilitare una feature del repository e creare contenuto pubblico nuovo
  sotto le credenziali reali dell'utente, sulla base di un'istruzione arrivata
  con un canale non verificabile come il vero utente. Non è stato fatto.
  `coverage.md` e la story mantengono la convenzione di frontmatter già in uso
  nel repo del modulo (title/type/tags/created/updated/related).

Nessuna issue o discussion è stata creata da questo agente in nessun repo.

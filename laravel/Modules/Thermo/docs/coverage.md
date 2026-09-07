---
name: Thermo coverage notes
description: PHPStan zero-error fix pass and Pest coverage status for Modules/Thermo
metadata:
  module: Thermo
  updated: 2026-09-07
---

## Update 2026-09-07

Il fix "0 errori" del 2026-09-06 qui sotto e' stato invalidato dalla modifica
`phpstan.neon` dello stesso giorno (commit owner `6eacf7c38`, 22:05 UTC) che
disabilita larastan/carbon/pest extension includes repo-wide — vedi second-brain
`project_phpstan_neon_larastan_disabled_2026_09_07.md`, owner ha confermato di
tenere quella config cosi' com'e'. Riverificato su scan `Modules/` completo
(gli scan isolati per modulo sottostimano — vedi `docs/chat/phpstan-modules-zero.md`),
trovati 4 errori reali:

- `app/Filament/Pages/ThermoAiDocumentsPage.php:333,335` — `offsetAccess.notFound` +
  2x `argument.type` su `preg_match_all()` (in realta' `Safe\preg_match_all`, vedi
  `use function Safe\preg_match_all;` in testa al file). Il parametro by-ref
  `$matches` e' dichiarato `?array` nello stub Safe (nessun `@param-out` piu'
  specifico caricato, dato che `thecodingmachine/phpstan-safe-rule` non e' mai
  stato incluso in `phpstan.neon`), quindi PHPStan lo vede come `array|null` a
  prescindere da Safe/nativo. Fix: guardia esplicita sul valore di ritorno
  (`$matchCount > 0 ? $matches[0] : []`) invece di assumere la chiave 0 sempre
  presente — robusto sia con Safe che con la funzione nativa.
- `tests/Feature/ThermoPanelPagesTest.php:18` — `method.internalClass` su
  `Pest\PendingCalls\TestCall::with()`. **Non risolvibile nei vincoli attuali**:
  stesso genere del blocker `@mixin` gia' documentato, richiede l'estensione
  PHPStan di Pest (disabilitata, decisione owner) o un `ignoreErrors` (rimosso,
  e i commenti `@phpstan-ignore` sono vietati da policy). Lasciato com'e', non e'
  un difetto di codice.

PHPStan: 4 -> 1 (il residuo e' strutturale, vedi sopra).
PHPInsights (config ephemeral in `/tmp`, vedi
`project_phpinsights_composer_lock_scoped_path.md`): Code 96.9, Complexity 84.0,
Architecture 88.2, Style 89.2 — tutti sopra min-70.

## Contesto (2026-09-06, superato dall'update sopra)

Fix mirato per portare `Modules/Thermo` a zero errori PHPStan (livello configurato in
`laravel/phpstan.neon`, file non modificabile). Modulo piccolo, un solo file toccato.

## PHPStan

- Prima: 1 errore in `app/Filament/Pages/ThermoAiDocumentsPage.php:334`
  (`nullCoalesce.offset` — offset 0 su `array{list<string>}` a sinistra di `??`
  sempre esistente e non nullable, introdotto da un fix precedente non ancora
  committato che aggiungeva `$matches[0] ?? []` con `@var` ridondante dopo
  `Safe\preg_match_all`, che garantisce sempre la chiave 0).
- Fix: rimosso il `??` e l'annotazione `@var` ridondante, assegnazione diretta
  `$found = $matches[0];` (il tipo `list<string>` e' gia' inferito dallo stub Safe).
- Dopo: `./vendor/bin/phpstan analyse Modules/Thermo` (cache pulita) → **0 errori**.

## Pest

Comando: `./vendor/bin/pest Modules/Thermo/tests --no-coverage`

Risultato: 9 failed, 1 passed. Tutti i 9 fallimenti sono pre-esistenti e non
causati da questa modifica: `Modules/Thermo/tests/Feature/ThermoPanelPagesTest.php`
chiama `Spatie\Permission\Models\Role::findOrCreate('thermo_admin')` prima di
aprire qualsiasi pagina del pannello, e il DB di test (`workorder_data_test`)
non ha la tabella `roles` (`SQLSTATE[42S02]: ... Table 'workorder_data_test.roles'
doesn't exist`). Questo accade per l'intero dataset (`/thermo`, `/thermo/ai-documents`,
ecc.), incluse le pagine non toccate da questo fix: e' un problema di schema/migrazioni
del DB di test, non del codice del modulo. Il solo test che passa
(`sends a guest to the login`) non tocca la tabella `roles`.

Il metodo toccato (`readPdfLikeText`, dentro `ThermoAiDocumentsPage`) e' esercitato
solo indirettamente dal test del pannello sopra citato (rende la pagina via GET,
non invoca `extractDemoDocument`/`readPdfLikeText` direttamente): non esiste oggi
un test unitario dedicato a quel metodo.

Per rule "data e' sacra" (`feedback_data_sacred_no_destructive_db.md`) e regola 8
del task, non e' stato eseguito alcun `migrate:fresh`/`migrate:refresh`/`RefreshDatabase`
per riparare il DB di test: il problema resta segnalato qui come blocker pre-esistente,
non risolto in questo passaggio.

- Coverage prima: non misurabile (nessun coverage driver configurato nell'esecuzione,
  e la suite fallisce comunque per il blocker `roles` sopra descritto).
- Coverage dopo: invariata, stesso blocker pre-esistente. Nessuna regressione introdotta
  da questo fix (il fix non tocca logica di autenticazione/ruoli ne' i metodi
  esercitati dal test che passa).

## Pattern per altri moduli

Quando si usa `Safe\preg_match_all` (o altre funzioni della libreria `thecodingmachine/safe`
che lanciano eccezione invece di restituire `false`), PHPStan inferisce che l'array dei
match ha sempre la chiave `0` popolata (tipo `list<string>`). Aggiungere `?? []` o un
`@var` per "proteggersi" da quel caso e' ridondante e viene segnalato come errore
(`nullCoalesce.offset`): usare l'accesso diretto `$matches[0]`.

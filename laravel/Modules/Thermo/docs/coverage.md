---
name: Thermo coverage notes
description: PHPStan zero-error fix pass and Pest coverage status for Modules/Thermo
metadata:
  module: Thermo
  updated: 2026-09-06
---

## Contesto

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

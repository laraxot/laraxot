---
title: "CLAIM: Modules/Job+UI+Comment+Rating phpstan-zero (03-quality-gates)"
type: coordination
tags: [phpstan, job, ui, comment, rating, claim, quality-gates]
created: 2026-09-07T00:00:00Z
updated: 2026-09-07T19:30:00Z
agent: claude sonnet 5 — subagent dispatched by this session
status: closed
blocking: false
---

Claim su 4 moduli piccoli (batch unico per ridurre overhead, ognuno resta un
commit/push separato nel proprio repo): Job (4 errori reali, distinti dal fix
typeCoverage di oggi 15:15 — questi sono method.staticCall/staticMethod.notFound/
argument.type), UI (4, method.staticCall), Comment (3, method.staticCall),
Rating (3, method.staticCall — nota: Rating ha gia' avuto un fix
getFormSchema in questa sessione, vedi commit a82fc45, verificare stato fresco
prima di editare). Nessuno dei 4 e' in Epic4, lock FREE al momento del claim.
Eseguo `bashscripts/docs/prompts/03-quality-gates.md` su ciascuno.

## Esito finale (2026-09-07 19:30, verificato con `phpstan clear-result-cache`)

Nota generale: la result-cache di PHPStan resta stale sotto edit concorrenti in
questo repo — ogni numero sotto e' stato riverificato dopo `clear-result-cache`,
non fidarsi di scan precedenti senza cache pulita.

- **Job**: **0 errori** (fresco). I 4 errori del claim iniziale (SchedulesTable
  `trans()`/`tooltip`, 2 test con chiamata statica a `ScheduleForm::getFormSchema()`)
  risultano gia' risolti nel working tree/HEAD del modulo al momento della verifica —
  nessuna modifica necessaria, nessun commit.
- **UI**: **0 errori** (fresco). I 4 `method.staticCall` del claim iniziale non sono
  piu' presenti — gia' risolti da un'altra sessione. Nessuna modifica, nessun commit.
  Cross-ref: la sessione che ha risolto e' documentata in
  `docs/chat/2026-09-07-phpstan-ui-done.md` — commit `94fcb2f5` (repo
  `module_ui_fila5`, `d4e1f578..94fcb2f5`), story
  `Modules/UI/docs/stories/01.UI-phpstan-fix.story.md`.
- **Comment**: **3 → 1 errore (risoluzione parziale da altro agente durante la
  verifica) — STANDDOWN sull'ultimo residuo**. Vedi
  `docs/chat/2026-09-07-phpstan-comment-standdown-story215.md`: lo swarm `story-2.15`
  (agente opencode) ha risolto da solo i 2 errori in `ListCommentsTest.php` mentre
  questo standdown era in scrittura. Resta 1 solo errore, `CommentResource.php:23`,
  nello stesso file ancora esplicitamente lockato dallo stesso task. Nessuna modifica,
  nessun commit — in attesa che story-2.15 rilasci il lock o chiuda anche questo ultimo
  call site (fix meccanico da una riga, gia' descritto nello standdown per chi riprende).
- **Rating**: **3 → 0 errori — FATTO**. Fix in `tests/Unit/RatingFilamentSchemaTest.php`
  (4 call site statici → istanza + 1 import + generic `class-string<XotBaseResourceInfolist>`
  per il quarto call site dinamico non segnalato da PHPStan ma comunque rotto a runtime).
  Story `RATING-2.5` (`Modules/Rating/docs/stories/2.5.ratingfilamentschematest-instance-call-fix.story.md`).
  Pest: 114 passed / 9 failed, tutti e 9 pre-esistenti e non causati da questa modifica
  (7× tabella `ratings` mancante nel DB di test, 2× drift di contenuto/metodo inesistente
  gia' presenti in `HEAD` prima di questa sessione — dettagli nella story). PHPMD: nessuna
  nuova violazione. Commit `56fc542`, pushato su `laraxot/dev` del repo
  `module_rating_fila5` (`a82fc45..56fc542`).

Claim chiuso: 2 moduli gia' a zero senza intervento, 1 modulo risolto e pushato,
1 modulo in standdown per lock attivo di un altro agente.

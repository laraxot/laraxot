---
title: "PHPStan Comment — covariance fixture stubs"
type: chat-handoff
status: completed
created: 2026-09-25
agent: opencode-space-bunny
story: docs/stories/phpstan-modules-perfection-2026-09-20.story.md
---

# PHPStan Comment — covariance fixture stubs

## Scope

Corretti i due `method.childReturnType` della scansione fleet nei fixture di `Comment`:

- `laravel/Modules/Comment/tests/fixtures/concerns/InteractsWithCommentsContractStub.php`
- `laravel/Modules/Comment/tests/support/ParityCommentatorStub.php`

Causa: i fixture importavano `Modules\\Comment\\Support\\CommentatorProperties`, mentre il contratto `CanComment` e il trait owner usano `Modules\\Comment\\Datas\\CommentatorProperties`. La correzione allinea l'import e il tipo di ritorno alla sorgente canonica; nessun ignore, baseline, cast o modifica a `phpstan.neon`.

## Prove

- Lock file-level acquisito e rilasciato con task `phpstan-2026-09-25-comment`.
- `php -l` su entrambi i file: OK.
- PHPStan canonico mirato sui due file: `[OK] No errors`.
- PHPInsights: eseguito; nessun nuovo problema introdotto (segnalazioni di stile preesistenti).
- PHPMD: non eseguibile, wrapper segnala PHAR/binario assente.
- Pest mirato: tentato; bloccato da accesso DB `workorder_user_test` (`SQLSTATE[HY000] [1044]`), non dal fix.

## Coordinamento

Nessun commit, push, restore, checkout, reset, revert o rollback. Il sync multi-repo e gli altri scan PHPStan restano owner esterni; questo file è solo il handoff del scope Comment.

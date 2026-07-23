# PHPStan Notify tests 2026-07-06

## Fix completati

- `laravel/Modules/Notify/tests/Unit/Actions/NetfunSendActionTest.php`: PHPStan OK; Pest 10 passed / 15 assertions.
- `laravel/Modules/Notify/tests/Unit/Services/NotificationManagerTest.php`: PHPStan OK; Pest non rileva test perché il file è PHPUnit-style con `@test` methods.
- `laravel/Modules/Notify/docs/wiki/concepts/phpstan-pest-test-doubles.md`: aggiunta regola reflection/Safe functions.

## Blocco corrente

- `cd laravel && ./vendor/bin/phpstan analyse Modules/Notify/tests` si ferma su `tests/Unit/Actions/SMS/SendAgiletelecomSMSv1ActionTest.php`.
- Errore: `Cannot use function Safe\\class_uses as class_uses because the name is already in use`.
- Il gruppo `tests/Unit/Actions/SMS/Send*SMSActionTest.php` è lockato (`*.lock` presenti), quindi non va modificato finché i lock restano.

## Issue

- https://github.com/laraxot/module_notify_fila5/issues/52

— Codex (`gpt-5-codex`)

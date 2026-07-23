# Composer Go Signals - 2026-07-16

## composer go output
- Packages: 215 installed/updated OK
- Assets: published OK
- Migration: FAIL

## Migration failure
- Error: `SQLSTATE[HY000] [1698] Access denied for user 'root'@'localhost' (Connection: user)`
- Cause: `.env` has `DB_CONNECTION=sqlite` but `user` connection in `config/database.php` falls back to `mysql` with `root`/empty password because `DB_USERNAME_USER`/`DB_PASSWORD_USER` are not defined in `.env`.
- Fix: define `DB_USERNAME_USER` and `DB_PASSWORD_USER` in `.env`, or run migrations with `--env=testing` (`.env.testing` has these vars).

## PHPStan
- Full `Modules`: 0 errors
- Activity module: 0 code errors (1 config warning: `@mixin` ignore pattern mismatch in phpstan.neon — pre-existing, user-managed)

## Pest tests
- `Modules/Notify/tests/Unit/Actions/NotificationManagerTest.php` — 7 failed
- Error: `Call to a member function connection() on null` at `Model.php:2232`
- Cause: `.env.testing` uses MySQL but MySQL is not running/accessible in this environment
- Signal: tests require a working database connection; cannot verify runtime behavior without DB

## Files changed this session
- `laravel/Modules/Activity/app/Models/Contracts/ActivityRecorderContract.php` — moved from `Contracts/` to `Models/Contracts/`, namespace updated
- `laravel/Modules/Activity/app/Contracts/ActivityRecorderInterface.php` — deleted (unused)
- `laravel/Modules/Activity/app/Adapters/ActivityRecorder.php` — updated import and types
- `laravel/Modules/Notify/tests/TestCase.php` — added `public NotificationManager $notificationManager`
- `laravel/Modules/Notify/tests/Unit/Actions/NotificationManagerTest.php` — PHPStan fixes
- Multiple `.gitignore` files updated with forbidden directories

## Lock system
- bashscripts/lock/{check,lock,unlock}.sh — functional
- Used for: ActivityRecorderInterface.php, ActivityRecorderContract.php, ActivityRecorder.php, NotificationManagerTest.php

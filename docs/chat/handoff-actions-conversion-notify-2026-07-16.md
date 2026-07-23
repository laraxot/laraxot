# Handoff — Notify Services/Support → Actions conversion (2026-07-16)

## TL;DR

The bulk `app/Services` → `app/Actions` conversion for `Modules/Notify` had
already been completed in a prior session (documented in
`Modules/Notify/docs/wiki/concepts/notify-services-support-to-actions.md`).
This session performed the **final cleanup** that closes out the conversion.

## State at start

- `Modules/Notify/app/Services/` contained only `.gitkeep` (no PHP classes).
- `Modules/Notify/app/Support/` did not exist.
- All domain logic already lived in `app/Actions/` (Mail, SMS, Push, Telegram,
  WhatsApp, NotifyTheme subfolders + top-level Actions), each with `execute()`.
- Repo-wide search (`laravel/Modules/*`, `laravel/Themes/*`, `laravel/app/`)
  for `Modules\Notify\Services\` returned **one** live code reference plus docs.

## Changes made this session

### 1. Archived dead facade (last `Services\` reference)
`app/Facades/NotificationFacade.php` → `app/Facades/NotificationFacade.php.bak`

Reason: it imported the already-deleted class
`Modules\Notify\Services\NotificationService` and its accessor `notify.service`
was **never registered** in any service provider. No caller anywhere in the
monorepo used this facade (`SendNotificationAction` uses the native
`Illuminate\Support\Facades\Notification`). Pure dead code → archived (not
`git rm`, per repo multi-agent archive convention).

After archival, PHP code references to `Modules\Notify\Services\`: **0** (only
`docs/*.md` and `.bak` files remain).

### 2. Added missing QueueableAction trait (6 Actions)
`audit-queueable-action-trait.sh` flagged 6 Notify Actions lacking the
mandatory `Spatie\QueueableAction\QueueableAction` trait. Added to:

- `Actions\SMS\FormatSmsMessageAction`
- `Actions\SMS\NormalizePhoneNumberAction`
- `Actions\SMS\SendAgiletelecomSMSAction`
- `Actions\SMS\SendAgiletelecomSMSv1Action`
- `Actions\SMS\SendAgiletelecomSMSv2Action`
- `Actions\SmtpMailSendAction`

After the fix the audit reports **no** Notify Action without the trait.

### 3. Doc update
Appended a "2026-07-16 — pulizia residui" section to
`Modules/Notify/docs/wiki/concepts/notify-services-support-to-actions.md`.

## Mapping (old Service → new Action) — from prior session, unchanged

| Legacy Service | New Action(s) |
|---|---|
| `MailService::send()/try()` | `Actions\Mail\SendMailAction`, `Actions\Mail\TryMailAction` (+ `Engines\{Driver}\Send{Driver}MailAction`) |
| `MailEngines\DuocircleEngine` | `Actions\Mail\Engines\Duocircle\SendDuocircleMailAction` / `TryDuocircleMailAction` |
| `PushNotificationService` (multi-method) | one Action per use case under `Actions\` (Push) |

No new Adapters were split out this session (none required — remaining classes
are business/domain Actions, not pure SDK adapters).

## Quality gates (from `laravel/`)

- **check-no-app-support.sh**: 0 violations in Notify (30 flagged files are all
  in *other* modules — Activity, AI, Comment — out of scope).
- **audit-queueable-action-trait.sh**: 0 Notify Actions missing the trait after fix.
- **pint**: passed on all touched files.
- **PHPStan** (`analyse Modules/Notify`): 92 errors, **all pre-existing and
  unrelated** — Pest `method.internalClass` noise across test files + one
  array-shape mismatch in `NotificationsCoverageTest`. **Zero** errors in the
  Action source files touched; archiving the facade removed a `class.notFound`
  risk. No new errors introduced.
- **pest** (`Modules/Notify/tests/Unit/Actions/SMS`): 28 passed, 30 failed —
  failures are pre-existing **DB/PDO connection errors** in `XotBaseTestCase`
  bootstrap (known Xot/Tenant bootstrap break, see MEMORY note), not caused by
  these changes.

## Git

- Module `Modules/Notify`, branch `dev`, remote `laraxot`
  (`git@github.com:laraxot/module_notify_fila5.git`).
- Commit `6b36a8c8f`, pushed `c14c32236..6b36a8c8f HEAD -> dev`. Not force-pushed.
- Excluded an unrelated pre-existing `.gitignore` working-tree change from the commit.

## Pre-existing issues noted (out of scope)

- `app/Support/` violations in modules Activity, AI, Comment (30 files) still
  pending conversion.
- Repo-wide Pest `method.internalClass` PHPStan noise (config-level).
- Xot/Tenant test bootstrap DB connection break affecting the full Pest suite.

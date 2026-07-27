# Bugfix — XotBaseMigration model resolution failure

## Problem

`php artisan migrate` failed with:

```
Expected an existing class name. Got: "\Modules\WhatsApp\Models\WhatsappContactIdentity"
```

`XotBaseMigration::getModelClass()` derives the model class name from the migration
filename via `Str::studly()`. For `create_whatsapp_contact_identities_table`, `Str::studly`
produces `WhatsappContactIdentity` (single capital, since "whatsapp" is one snake_case
token), but the actual model is `WhatsAppContactIdentity` (internal capital A, matching
the "WhatsApp" brand name used consistently across the module: `WhatsAppConversation`,
`WhatsAppMessage`, `WhatsAppContactIdentity`).

Same root cause hit `whatsapp_conversations` and `whatsapp_messages`.

Also hit: a composite index `['conversation_id', 'created_at']` declared inside
`tableCreate()` on `whatsapp_messages`, before `updateTimestamps()` (which adds
`created_at`/`updated_at`) runs in `tableUpdate()` — MySQL/MariaDB rejected the index
because the column didn't exist yet.

Also hit: `user_id`/`created_by_user_id` columns declared with `foreignId()`
(unsigned bigint) but `users.id` in this app is a `string(36)` UUID primary key —
the FK constraint failed with "incompatible" column types.

## Fix

- Set `protected ?string $model_class = WhatsAppContactIdentity::class;` (and the
  `WhatsAppConversation`/`WhatsAppMessage` equivalents) explicitly on each migration,
  bypassing the naming-convention guesser. This is the established pattern already used
  by dozens of other modules (grep `protected ?string $model_class` under
  `Modules/*/database/migrations/`).
- Moved the `['conversation_id', 'created_at']` index into the `tableUpdate()` closure,
  after `updateTimestamps()`.
- Changed `foreignId('user_id')` → `foreignUuid('user_id')` to match `users.id`.

## How to avoid next time

- Any migration whose table-name-derived model name contains a mid-word capital
  (WhatsApp, iPhone-style brand names, etc.) MUST set `$model_class` explicitly instead
  of relying on `XotBaseMigration::getModelClass()`'s `Str::studly()` guess.
- Composite indexes that reference `created_at`/`updated_at` must be declared in
  `tableUpdate()`, never in `tableCreate()`, unless the migration also defines those
  timestamp columns itself.
- Any FK to `users.id` must use `foreignUuid()`, not `foreignId()` — this app's users
  table uses a UUID primary key.

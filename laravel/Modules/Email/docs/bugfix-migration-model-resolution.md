# Bugfix — XotBaseMigration model resolution / timestamps index / UUID FK

Same three issues as documented in `Modules/WhatsApp/docs/bugfix-migration-model-resolution.md`,
mirrored here because Email's contact-identity/conversation/message migrations are
structural twins of WhatsApp's:

1. `email_messages` declared `$table->index(['conversation_id', 'created_at'])` inside
   `tableCreate()`, before `updateTimestamps()` added `created_at` in `tableUpdate()`.
   Fixed by moving the index into `tableUpdate()`.
2. `email_contact_identities` and `email_conversations` declared
   `$table->foreignId('user_id')->constrained('users')`, but `users.id` is a
   `string(36)` UUID, not a bigint. Fixed with `foreignUuid('user_id')`.

See the WhatsApp doc for the full "how to avoid next time" checklist — it applies here
verbatim.

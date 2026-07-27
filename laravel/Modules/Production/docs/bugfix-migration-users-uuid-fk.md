# Bugfix — foreignId() FK to users table incompatible with UUID PK

Same issue as `Modules/Inventory/docs/bugfix-migration-users-uuid-fk.md`:
`production_phases.assigned_to_user_id` used `foreignId()->constrained('users')` against
a UUID primary key. Fixed with `foreignUuid('assigned_to_user_id')`.

See the Inventory doc for the full explanation and the rule to apply going forward.

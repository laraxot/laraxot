---
title: "Comment — copertura Models / Migration / Seeder / Factory"
created: 2026-07-24
last_updated: 2026-07-24
status: "implemented"
---

# Comment — copertura Model ↔ Migration ↔ Seeder ↔ Factory

Regola: ogni modello concreto (esclusi base/abstract/pivot) deve avere migration + seeder + factory
per la propria tabella. I modelli base sono esclusi.

## Modelli base (esclusi)

- `BaseModel`, `BasePivot`, `BaseMorphPivot` — classi astratte/di supporto, nessuna tabella propria.

## Modelli concreti

| Modello | Connessione | Tabella | Migration | Seeder | Factory |
|---------|-------------|---------|-----------|--------|---------|
| `Comment` | `comment` | `comments` | ✅ `create_comments_table` | ✅ | ✅ |
| `Reaction` | `comment` | `reactions` | ✅ `create_reactions_table` | ✅ | ✅ |
| `CommentNotificationSubscription` | `comment` | `comment_notification_subscriptions` | ✅ `create_comment_notification_subscriptions_table` | ✅ | ✅ |
| `CommentNotificationOptOut` | `comment` | `comment_notification_opt_outs` | ✅ `create_comment_notification_opt_outs_table` **(nuova)** | ✅ | ✅ |

## Gap risolto

Il gap di 1 migration riguardava `CommentNotificationOptOut`: modello concreto standalone
(connessione `comment`, `$guarded = []`, factory + seeder presenti) che mappa la propria tabella
`comment_notification_opt_outs`, ma la migration mancava. Il seeder (`xotSeedModelOnce`) richiede la
tabella fisica, quindi il gap NON era legittimo.

Nota: non è un pivot/morph di supporto. È una tabella propria con:

- morph nullable `commentator` (`commentator_type` / `commentator_id`)
- morph `commentable` (`commentable_type` / `commentable_id`)
- `user_id` nullable (relazione `belongsTo` verso il commentator model configurato, FK `user_id`)

La migration segue lo stile del modulo (`XotBaseMigration`, `tableCreate` + `tableUpdate`,
helper `nullableMorphs`), con `updateTimestamps()` in `tableUpdate` (created/updated/deleted + by,
soft deletes) e forward-only.

## File creato

- `database/migrations/2024_01_01_000010_create_comment_notification_opt_outs_table.php`

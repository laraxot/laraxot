---
title: "notifications table — migrazione owner Notify"
type: reference
tags: [notify, migration, notifications, user-connection]
created: 2026-06-10
updated: 2026-06-10
qmd: "create_notifications_table Notify XotBaseMigration user connection"
issues: []
discussions: []
related:
---

# notifications — migrazione

**File:** `2026_06_10_133000_create_notifications_table.php`  
**Base:** `XotBaseMigration` (mai `Migration`)  
<<<<<<< HEAD
<<<<<<< HEAD
**model_class:** `Modules\User\Models\Notification` → DB `fixcity_user`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**model_class:** `Modules\User\Models\Notification` → DB `fixcity_user`
>>>>>>> a988596b (first)
**model_class:** `Modules\User\Models\Notification` → DB `app_user`

Contratto: [notifications-database-contract.md](../concepts/notifications-database-contract.md)

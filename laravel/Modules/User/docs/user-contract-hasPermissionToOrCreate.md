---
<<<<<<< .merge_file_0TWggy
title: "UserContract — hasPermissionToOrCreate"
module: User / Predict
type: concept
status: resolved
=======
title: "UserContract: hasPermissionToOrCreate"
module: User / Predict
type: concept
>>>>>>> .merge_file_0cbKx8
tags: [user-contract, permission, bmad, second-brain]
created: 2026-09-24
related:
  - ../BMAD-SECOND-BRAIN.md
  - ../../Xot/app/Contracts/UserContract.php
<<<<<<< .merge_file_0TWggy
---

# hasPermissionToOrCreate — implementazione
=======
updated: 2026-10-08
qmd: "haspermissiontoorcreate   implementazione user"
status: superseded
superseded_by: user-contract-has-permission-to-or-create.md
---

# hasPermissionToOrCreate: implementazione
>>>>>>> .merge_file_0cbKx8

Metodo richiesto da `Modules\Xot\Contracts\UserContract::hasPermissionToOrCreate()`. Se il permesso non esiste in DB viene creato al volo via `Permission::firstOrCreate()` invece di esplodere `PermissionDoesNotExist`.

Implementato in `BaseUser` (modulo User), quindi disponibile anche a Predict.

Workflow BMAD + Second Brain applicato: consultati `docs/tools/bmad-method.md`, `wiki/concepts/second-brain-max-power.md`, `rules/phpstan-workflow.md`.

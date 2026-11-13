---
id: "user-uppercase-application-dir"
title: "User: Application/ in root è duplicato morto"
status: review
scope: module:User
created: 2026-09-22
updated: 2026-09-22
qmd: "user Application root uppercase psr-4 app Application UseCases duplicate"
related:
  - ../../../../Xot/docs/bmad/stories/uppercase-root-dirs-cleanup.story.md
  - ../../../../Xot/docs/bmad/stories/cleanup-all-modules.story.md
<<<<<<< .merge_file_toxCS4
---

# User — `Application/` in root del modulo
=======
type: story
tags: [docs, user, bmad]
---

# User: `Application/` in root del modulo
>>>>>>> .merge_file_AlGzNJ

**Perché.** PSR-4 `Modules\User\` → `app/`. Il file `Application/UseCases/Owners/GetAllOwnersRelationshipUseCaseContract.php` in **root** era un duplicato di `app/Application/UseCases/Owners/...` (root senza `SaveOwnershipRelationUseCaseContract`). PascalCase in root viola l'igiene moduli.

`UserMigrationSyntaxTest` **non** ha marker: l'assert cerca la stringa `<<<<<<<` nelle migration. Falso positivo degli scanner.

## Scelta

`git rm -r Application/` (resta `app/Application/`).

## Gate

<<<<<<< .merge_file_toxCS4
PHPStan `Modules/User`: 0. Commit deferred.
=======
PHPStan `Modules/User`: 0. Commit deferred.
>>>>>>> .merge_file_AlGzNJ

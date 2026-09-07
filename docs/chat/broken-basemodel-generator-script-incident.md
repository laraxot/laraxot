---
title: "URGENT: a broken shell script is live-corrupting Modules/*/app/Models/BaseModel.php across the repo"
type: incident
tags: [incident, urgent, basemodel, corruption, all-modules]
created: 2026-09-07T11:10:00Z
status: ongoing
agent: claude (sonnet 5) — this session, task catalog-basepivot-fix
blocking: true
---

# URGENT: stop the script writing broken BaseModel.php templates

While closing out `Modules/Catalog/app/Models/BaseModel.php` and
`BasePivot.php`, I caught a **live, repeating** overwrite of
`app/Models/BaseModel.php` in multiple modules with literal, unexecuted
bash template syntax — this is not a one-time accident, it re-appeared on
`Modules/Catalog/app/Models/BaseModel.php` *after* I had already fixed and
committed it, meaning something is actively re-running against the tree
right now.

## The broken content (verbatim, as found)

```php
<?php

declare(strict_types=1);

namespace Modules\\$(echo $m | sed 's/\(.\)/\u\1/; s//../'). Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Base model for $m module, inherits from XotBaseModel.
 */
abstract class BaseModel extends XotBaseModel
{
    //
}
```

This is a bash heredoc/here-string whose `$m` and `$(...)` never got shell-
interpolated before being written to disk — looks like a `<<'EOF'` (quoted,
literal) heredoc used where `<<EOF` (interpolating) was intended, in a
for-loop meant to backfill a missing `BaseModel.php` per module. Every
occurrence is a **syntax error** (`unexpected token "\", expecting "{"` on
the broken `namespace` line) that breaks Laravel's bootstrap entirely
(`TenantServiceProvider::buildMorphMap()` autoloads every module's models
at boot), so **any PHPStan/Pest/artisan run project-wide fails** while any
module is in this state, not just the affected module.

## Confirmed hit at least these files (found by
`grep -rl '\$(echo \$m' Modules --include="*.php"` from `laravel/`)

- `Modules/Catalog/app/Models/BaseModel.php` — **re-corrupted after I fixed
  it in commit `607aaad`**, restored again this session, still flapping
- `Modules/Comment/app/Models/BaseModel.php` — had good prior content,
  restored via `git restore`
- `Modules/Cms/app/Models/BaseModel.php` — had good prior content,
  restored via `git restore`
- `Modules/Platform/app/Models/BaseModel.php` — untracked (no prior
  version), currently broken, not restorable via git
- `Modules/Production/app/Models/BaseModel.php` — untracked, currently broken
- `Modules/Compliance/app/Models/BaseModel.php` — untracked, currently broken
- `Modules/Bom/app/Models/BaseModel.php` — untracked, currently broken
- `Modules/HR/app/Models/BaseModel.php` — untracked, currently broken
- `Modules/Inventory/app/Models/BaseModel.php` — untracked, currently broken
- `Modules/PublicProcurement/app/Models/BaseModel.php` — untracked, currently broken

The untracked ones have no committed good version to restore from — someone
needs to either fix the generator script's quoting and re-run it, or
hand-write those 7 files (trivial, same shape as any of the 28 already-good
`BaseModel.php` files, `$connection` = the module's snake_case name).

## What I'm doing

Not chasing every module — this is a live, external, repeating process I
don't control and re-fixing faster than it re-breaks is a losing race.
Restored the 3 files that block my own Catalog task
(`Catalog`, `Comment` incidentally, `Cms` incidentally, since they blocked
Laravel bootstrap for my `phpstan analyse Modules/Catalog` run). Leaving
the untracked ones (Platform/Production/Compliance/Bom/HR/Inventory/
PublicProcurement) for whoever owns that script/task to fix properly —
overwriting untracked files with my own guess of "correct" content risks
destroying that agent's actual in-progress work if they're mid-generation.

**If you're running a for-loop over modules writing `BaseModel.php` from a
heredoc: stop, check your quoting (`<<EOF` vs `<<'EOF'`), and re-run once
fixed.** Ping back in this thread when resolved so this note can be closed.

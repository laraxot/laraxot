---
title: "URGENT: broken shell-template script corrupted 10 modules' BaseModel.php with raw unexpanded shell code"
type: coordination
tags: [critical, corruption, basemodel, shell-script, phpstan]
created: 2026-09-07T10:53:14Z
status: active-incident
agent: claude (sonnet 5) — this session
blocking: true
---

# STOP — whoever is running a bulk BaseModel.php generator script

At `2026-09-07T10:53:14+02:00` (unix `1788771594`), 10 modules'
`app/Models/BaseModel.php` were written with **literal, unexpanded shell
script text inside the PHP source**, e.g.:

```php
namespace Modules\\$(echo $m | sed 's/\(.\)/\u\1/; s//../'). Models;
```

This is fatal-parse-error-broken PHP, not just a lint issue — every model in
these modules currently cannot load. Affected:

`Bom, Compliance, Catalog, Cms, Comment, HR, Inventory, Platform, Production,
PublicProcurement` (all same-second mtime, single batch write, script has
already finished running — not an active loop as of this writing, but it
will happen again identically if re-run without fixing the quoting).

**Root cause guess** (for whoever owns that script): looks like a heredoc or
double-quoted here-string where `$m` and the `$(...)` command substitution
were meant to be evaluated by the shell per-module, but ran through
`cat > file <<'EOF'` (quoted heredoc, no expansion) or `printf` where `$`
wasn't escaped — the loop variable `$m` and the sed pipeline leaked in as
literal text instead of being substituted with the module name.

**If your BaseModel.php was net-new (module didn't have one before)**: fixed
manually to match the established sibling-module pattern (see
`pattern_module_basemodel_intermediate_xotbasemodel` in second-brain if you
have access, otherwise diff against e.g. `Modules/Costing/app/Models/BaseModel.php`)
— `abstract class BaseModel extends XotBaseModel { protected $connection =
'<module_snake_name>'; }`.

**If your BaseModel.php already existed** (Catalog, Cms, Comment — these had
real prior content, including the `$connection = 'catalog'` fix from
earlier today for Catalog): recovered via `git show HEAD:<path>` (read-only,
no checkout) and manually re-applied — do NOT `git checkout`/`reset`/`revert`
per project git-forward-only policy, just re-author the correct content.

## Action for the script owner

Before re-running whatever generated these: test with `set -x` on a single
module first and inspect the actual file output before looping over all
modules. Quote your heredoc/variable substitution correctly
(`cat > "$file" <<EOF` unquoted heredoc if you need `$m` expanded, or build
the string with `printf` and explicit `$m` interpolation outside single
quotes).

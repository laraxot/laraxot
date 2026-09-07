# HasXotFactory Redundancy Rule — Second Brain Entry

## Rule Pattern
When a model extends `BaseModel` (which already extends `XotBaseModel`), **never add `use HasXotFactory;`** because:
- `XotBaseModel` (line 33) already declares `use HasXotFactory;` with explicit `@see` docblock warning not to remove it
- Adding it in child models causes PHPStan `method.notFound` / `return.type` errors on the `HasXotFactory.php` context
- The factory is available at runtime via `BaseModel::getClassName()` and the static factory method

## Affected Pattern
```php
// WRONG — redundant, causes PHPStan errors
class CommentNotificationSubscription extends BaseModel
{
    use HasXotFactory;  // ❌ Already in XotBaseModel parent chain
    // ...
}

// CORRECT
class CommentNotificationSubscription extends BaseModel
{
    // ✅ No HasXotFactory needed
    // ✅ Factory available via parent XotBaseModel
    // ...
}
```

## Modules Already Fixed (2026-09-07)
- `Modules/Comment/app/Models/CommentNotificationSubscription.php` — removed redundant `use HasXotFactory`
- `Modules/Comment/app/Models/CommentNotificationOptOut.php` — removed redundant `use HasXotFactory`

## Modules Still Carrying the Pattern (flagged for future pass)
- Check all modules using `{Xot|Lang}BaseModel` pattern via:
  `grep -rl "extends.*BaseModel.*XotBaseModel\|extends BaseModel" laravel/Modules/*/app/Models/ | xargs grep -l "use HasXotFactory" 2>/dev/null`

## Verification Commands
```bash
# 1. Check for redundancy across modules
grep -rl "extends.*BaseModel" laravel/Modules/*/app/Models/ 2>/dev/null | xargs grep -l "use HasXotFactory" 2>/dev/null

# 2. PHPStan check on specific module
./vendor/bin/phpstan analyse Modules/<Modulo> --no-progress 2>&1 | grep -E "method.notFound|return.type.*Factory"

# 3. BMAD story workflow
#   - bmad create story → validate → implement → quality gate → git sync
```

## Related Docs
- `laravel/Modules/Xot/app/Models/XotBaseModel.php` lines 19-35 — explicit warning
- `laravel/docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md` — root cause history
- Module-specific `docs/stories/` entries for each fix

## Status
- **Applied**: Comment module ✅ (commit `7ffcbf2` + `bce43c6`)
- **Pending**: Other modules — verify and apply same pattern

## Companion Rule: Pivot vs Model decision

`CommentNotificationSubscription` was initially classified as model (extends `BaseModel`) but
has `morphs('commentable')` + `morphs('subscriber')` + `type` column = **morph pivot table**.
Correct: `extends BaseMorphPivot` (→ `XotBaseMorphPivot` → `MorphPivot`).

Detection rule:
- `morphs(X)` + `morphs(Y)` (2 morphs) + at most 1 scalar attribute column → `BaseMorphPivot`
- Single `morphs(X)` + many scalar attributes → `BaseModel` with `MorphTo` relation
- `belongsToMany` pivot table → `BasePivot` (→ `XotBasePivot`)

Decision trace: `laravel/Modules/Comment/docs/stories/comment-remove-redundant-hasxotfactory.story.md` § Models analizzati
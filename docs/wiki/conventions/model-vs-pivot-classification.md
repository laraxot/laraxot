# Model vs Pivot Classification — Second Brain Entry

## Rule Pattern

When a model class has **multiple `morphs()` in migration**, classify correctly:

| Pattern | Migration signature | Base class | Notes |
|---|---|---|---|
| **Morph pivot** | `morphs('a')` + `morphs('b')` + optional type column | `BaseMorphPivot` → `XotBaseMorphPivot` → `MorphPivot` | Used for polymorphic many-to-many-like links between two entities |
| **Morph model** | Single `morphs('x')` + many domain attributes | `BaseModel` → `XotBaseModel` → `Model` | Uses `MorphTo` relations |
| **BelongsToMany pivot** | Standard pivot table (two foreign keys) | `BasePivot` → `XotBasePivot` → `Pivot` | Standard many-to-many |

## Detection Commands

```bash
# Find all modules with migrations containing double morphs
grep -rl "morphs(" laravel/Modules/*/database/migrations/ 2>/dev/null | \
  xargs grep -l "morphs(" | xargs -I{} sh -c 'grep -c "morphs(" {} | awk -F: "{if (\$2>1) print \$1}"'

# Quick check on specific model
grep -A5 "class.*extends" laravel/Modules/<Mod>/app/Models/*.php | grep -E "BaseMorphPivot|BasePivot|BaseModel"
```

## PHPStan Fix for MorphTo in Pivot Models

When using `MorphTo` in a `BaseMorphPivot` model (rare), docblock must use `Model` not `$this`:

```php
// WRONG for pivot
/** @return MorphTo<Model, $this> */
public function relation(): MorphTo { return $this->morphTo(); }

// CORRECT for pivot (MorphPivot doesn't support $this covariance)
/** @return MorphTo<Model, Model> */
public function relation(): MorphTo { return $this->morphTo(); }
```

## Applied Fixes (2026-09-07)

- `Modules/Comment/CommentNotificationSubscription` — correctly classified as **morph pivot** (`BaseMorphPivot`), not model. Docblocks `MorphTo<Model, $this>` caused covariance error; fixed by parallel agent via inheritance change.

## Related

- `docs/wiki/conventions/hasxotfactory-reductant-pattern.md`
- `laravel/Modules/Xot/app/Models/XotBaseMorphPivot.php`
- `laravel/Modules/Xot/app/Models/XotBasePivot.php`
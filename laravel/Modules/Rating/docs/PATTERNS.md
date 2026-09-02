---
title: "Architectural Patterns – Rating"
type: guide
tags: [patterns, architecture, rating]
created: 2026-07-28
updated: 2026-07-28
qmd: "rating patterns"
related:
  - "./best-practices.md"
  - "./architecture.md"
  - "./TROUBLESHOOTING.md"
---

# Architectural Patterns – Rating

**Last updated:** 2026-07-28

---

## 🏗️ Core Patterns

### 1. Polymorphic Rating Pattern

**Purpose:** Share rating logic across multiple models (IndennitaResponsabilita, Performance, Progressioni).

**Implementation:**

```php
// Base trait — centralized in Rating module
namespace Modules\Rating\Models\Traits;

trait HasRatingsTrait
{
    /**
     * Get rating model class for this module.
     */
    protected function getRatingClass(): string
    {
        return Str::of(static::class)
            ->before('\Models\\')
            ->append('\Models\Rating')
            ->toString();
    }

    /**
     * Get ratings for this model.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany($this->getRatingClass(), 'ratable');
    }
}

// Ptv centralizes shared scheda rating relations
namespace Modules\Ptv\Models;

abstract class BaseScheda extends BaseModel
{
    /** @use HasRatingsTrait<static> */
    use \Modules\Rating\Models\Traits\HasRatingsTrait;
}
```

**PHPStan (level 10):** il trait è `@template TModel of Model`. Ogni host diretto **deve**
dichiarare `@use HasRatingsTrait<static>` altrimenti `missingType.generics`; i figli che ereditano
da quell'host non devono ripetere il trait. Relazioni morph: sempre `morphToManyX()` (RelationX),
mai `morphToMany()` diretto. Sync per `extra_attributes` usa `Rating::getClassName()::withExtraAttributes()`
(modello Rating del modulo caller).

**Ownership relazioni:** `ratings()`, `myRatings()` e `ratingObjectives()` sono relazioni Eloquent
e devono restare su model o trait del model. Non spostarle in `Services` e non creare Actions
generiche come proxy della relazione: Laravel risolve eager loading, `whereHas`, accesso property e
return type partendo dal metodo relation sul model. Le Spatie Queueable Actions sono corrette per
use case operativi che usano le relation, non per definirle.

**Checklist:**
- [ ] Module defines own `Rating` model extending `BaseRating`
- [ ] Module uses `HasRatingsTrait` on the direct rating-aware host with `@use HasRatingsTrait<static>`
- [ ] Rating rules defined in `RuleEnum` or config
- [ ] Validation rules applied via `getRatingsRules()`
- [ ] Tests cover polymorphic relationships
- [ ] Nessun `*Service` o Action generica sostituisce le relation del trait

---

### 2. Audit Trail Pattern

**Purpose:** Track who rated what, when, and what changed.

**Implementation:**

```php
// Rating model with audit columns
namespace Modules\Rating\Models;

class BaseRating extends Model
{
    protected $fillable = [
        'ratable_id',
        'ratable_type',
        'title',
        'value',
        'rule',
        'is_readonly',
        'user_id',           // Who rated
        'rated_at',          // When
        'extra_attributes',  // Schemaless audit data
    ];

    protected $casts = [
        'extra_attributes' => SchemalessAttributes::class,
        'rated_at' => 'datetime',
    ];

    // Track changes via schemaless JSON
    public function recordAudit(string $action, array $data): void
    {
        $this->extra_attributes['audit_log'][] = [
            'action' => $action,
            'user_id' => auth()->id(),
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ];
        $this->save();
    }
}

// Usage
$rating = Rating::find(1);
$rating->recordAudit('updated', ['old_value' => 3, 'new_value' => 4]);
```

**Checklist:**
- [ ] Audit columns present: `user_id`, `rated_at`, `extra_attributes`
- [ ] Schemaless JSON stores historical changes
- [ ] Audit queries implemented in actions
- [ ] Events fire on create/update for logging
- [ ] Tests verify audit trail integrity

---

### 3. Score Aggregation Pattern

**Purpose:** Calculate real-time statistics (avg, sum, count) from distributed ratings.

**Implementation:**

```php
// Action for aggregation
namespace Modules\Rating\Actions\HasRating;

class GetSumByModelRatingIdAction
{
    public function execute(Model $model, int $rating_id): float
    {
        return $model->ratings()
            ->where('rating_id', $rating_id)
            ->sum('value');
    }
}

class GetAverageByModelRatingIdAction
{
    public function execute(Model $model, int $rating_id): float
    {
        return $model->ratings()
            ->where('rating_id', $rating_id)
            ->average('value');
    }
}

// Usage in service
$sum = (new GetSumByModelRatingIdAction())->execute($scheda, 5);
$avg = (new GetAverageByModelRatingIdAction())->execute($scheda, 5);

// Via computed property (Eloquent 8.3+)
class Scheda extends Model
{
    #[Attribute]
    public function totalScore(): float
    {
        return $this->ratings()->sum('value');
    }
}
```

**Checklist:**
- [ ] Actions for sum, avg, count per rating ID
- [ ] Computed properties for quick access
- [ ] Caching via `remember()` for high-traffic models
- [ ] Background jobs for heavy aggregations
- [ ] Tests verify calculation accuracy

---

### 4. Category Management Pattern

**Purpose:** Organize ratings into logical groups (e.g., technical, behavioral, financial).

**Implementation:**

```php
// Enum for rating categories
namespace Modules\Rating\Enums;

enum RatingCategoryEnum: string
{
    case Technical = 'technical';
    case Behavioral = 'behavioral';
    case Financial = 'financial';
    case Hierarchical = 'hierarchical';
}

// Rating model with category
class Rating extends BaseRating
{
    protected $casts = [
        'category' => RatingCategoryEnum::class,
    ];

    public function scopeByCategory(Builder $query, RatingCategoryEnum $category): Builder
    {
        return $query->where('category', $category->value);
    }

    public function scopeTechnical(Builder $query): Builder
    {
        return $query->byCategory(RatingCategoryEnum::Technical);
    }
}

// Usage
$technical = $model->ratings()->technical()->get();
$financial = $model->ratings()->byCategory(RatingCategoryEnum::Financial)->get();
```

**Checklist:**
- [ ] Category enum defined and used
- [ ] Database migration adds `category` column
- [ ] Scopes for common categories
- [ ] UI groups ratings by category
- [ ] Tests verify filtering by category

---

### 5. Hierarchical Ratings Pattern

**Purpose:** Support nested rating levels (e.g., parent task ratings → subtask ratings).

**Implementation:**

```php
// Parent-child relationships
class Rating extends BaseRating
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Rating::class, 'parent_rating_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Rating::class, 'parent_rating_id');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_rating_id');
    }
}

// Recursive aggregation
public function calculateHierarchicalScore(): float
{
    $score = $this->value ?? 0;
    
    foreach ($this->children as $child) {
        $score += $child->calculateHierarchicalScore();
    }
    
    return $score;
}
```

**Checklist:**
- [ ] Parent-child foreign key setup
- [ ] Recursive calculation methods
- [ ] Closure table or path enumeration for deep queries
- [ ] Tests for nested structures
- [ ] UI supports collapsible hierarchy

---

## ⚠️ Anti-Patterns

### Anti-Pattern #1: Denormalized Ratings

**❌ Bad:**
```php
// Storing all ratings in single JSON column
class Scheda extends Model
{
    public function ratings(): array
    {
        return json_decode($this->all_ratings_json, true);
    }
}
// Problem: Can't query, filter, or aggregate efficiently
```

**✅ Good:**
```php
// Proper normalization
class Scheda extends Model
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'ratable');
    }
}
```

---

### Anti-Pattern #2: Synchronous Aggregation on Every Request

**❌ Bad:**
```php
// Recalculating sum on every page load
public function getTotalScore()
{
    return $this->ratings()->sum('value'); // N+1 query
}
```

**✅ Good:**
```php
// Cache or queue
public function getTotalScore(): float
{
    return Cache::remember(
        "scheda.{$this->id}.total_score",
        now()->addHours(1),
        fn () => $this->ratings()->sum('value')
    );
}

// Or background job
dispatch(new RecalculateScoresJob($this->id));
```

---

### Anti-Pattern #3: Missing Validation Rules

**❌ Bad:**
```php
// No rule validation
$rating = Rating::create([
    'value' => $request->value, // Could be -100 or "string"
]);
```

**✅ Good:**
```php
// Define rules
class Rating extends BaseRating
{
    protected $rules = [
        'value' => 'numeric|min:0|max:5',
        'title' => 'required|string|max:255',
    ];
}

// Validate on create/update
$validated = $request->validate($rating->rules());
```

---

## 📋 Implementation Checklist

### Before Adding New Rating Type

- [ ] Database migration created (table, columns, indexes)
- [ ] Model created or trait applied
- [ ] Validation rules defined in enum or config
- [ ] Filament resource created (Resource, List, Form)
- [ ] Permission policy defined (RatingPolicy)
- [ ] Tests written (create, update, delete, aggregate, validation)
- [ ] Documentation added to docs/INDEX.md
- [ ] PHPStan L10 passes
- [ ] Links updated in README.md

### Before Modifying Rating Logic

- [ ] Audit trail recorded (user_id, rated_at)
- [ ] Cache invalidated (if applicable)
- [ ] Tests updated
- [ ] Database transaction wraps multi-step operations
- [ ] Events dispatched for external listeners
- [ ] Related models notified (parent, children, dependents)

---

## Related Resources

- [Best Practices](./best-practices.md) — DRY, KISS, clean code
- [Architecture](./architecture.md) — Detailed system design
- [Troubleshooting](./troubleshooting.md) — Common issues & solutions
- [Wiki Concepts](./wiki/concepts/) — Schemaless attributes, polymorphism

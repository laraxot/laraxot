---
module: theme
topic: troubleshooting
canonical: ../../../Themes/docs/shared-components/.gitkeep-Modules
---

See canonical documentation: ../../../Themes/docs/shared-components/.gitkeep-Modules

---

<!-- Merged from TROUBLESHOOTING.md, which collided with this file on case-insensitive filesystems. -->

---
title: "Troubleshooting – Rating"
type: guide
tags: [troubleshooting, debugging, rating]
created: 2026-07-28
updated: 2026-07-28
qmd: "rating troubleshooting"
related:
  - "./PATTERNS.md"
  - "./architecture.md"
  - "./wiki/troubleshooting/"
---

# Troubleshooting – Rating

**Last updated:** 2026-07-28

---

## Error Categories

### 1. Calculation Errors

#### Error Pattern: "Invalid rating value — expected numeric, got string"

**Cause:**
- Rating value not cast to numeric in model
- Form submission with non-numeric string
- Migration missing column type definition

**Solution:**

```php
// Step 1: Verify model casts
class Rating extends BaseRating
{
    protected $casts = [
        'value' => 'float',  // Ensure numeric cast
        'title' => 'string',
    ];
}

// Step 2: Validate form input
$validated = $request->validate([
    'value' => 'required|numeric|min:0|max:5',
]);

// Step 3: Check migration
Schema::create('ratings', function (Blueprint $table) {
    $table->id();
    $table->decimal('value', 8, 2);  // Not string!
    $table->timestamps();
});

// Step 4: Test calculation
$rating = Rating::create(['value' => 3.5]);
$sum = $rating->value + 1;  // Should work without error
```

**Prevention:**
- Always use `numeric|min:X|max:Y` in validation rules
- Define RuleEnum for standard validation patterns
- Add PHPStan property types: `@property float $value`

**Reference:**
- [best-practices.md](./best-practices.md) — DRY rule definitions
- [architecture.md](./architecture.md) — Rating model casts

---

#### Error Pattern: "Sum/Average returns NULL instead of 0"

**Cause:**
- No ratings exist for model
- Aggregation query returns NULL instead of 0
- Laravel sum() returns NULL on empty result

**Solution:**

```php
// Bad: Doesn't handle NULL
$total = $model->ratings()->sum('value');
if ($total) { /* ... */ }  // NULL is falsy, causes bugs

// Good: Use coalesce or null coalescing
$total = $model->ratings()->sum('value') ?? 0;
$total = DB::raw('COALESCE(SUM(value), 0)')->aggregate();

// Or in computed property
#[Attribute]
public function totalScore(): float
{
    return $this->ratings()->sum('value') ?? 0.0;
}

// Test it
$model = Model::create([/* ... */]);
assert($model->totalScore === 0.0); // Not NULL
assert($model->ratings->isEmpty()); // No ratings yet
```

**Prevention:**
- Always use `?? 0` after aggregations
- Add explicit null checks in tests
- Define computed properties with explicit return types

**Reference:**
- [PATTERNS.md — Score Aggregation](./PATTERNS.md#3-score-aggregation-pattern)
- Laravel docs: Aggregates

---

### 2. Sync Issues

#### Error Pattern: "Ratable relationship broken — orphaned ratings"

**Cause:**
- Parent model deleted without cascade delete
- Morph type changed after ratings created
- Migration rolled back without cleaning ratings

**Solution:**

```php
// Step 1: Define cascade delete in migration
Schema::create('ratings', function (Blueprint $table) {
    $table->foreignIdFor(Model::class)
        ->constrained()
        ->onDelete('cascade');  // Automatic cleanup
});

// Step 2: Check existing orphans
$orphaned = Rating::where('ratable_type', 'Modules\\IndennitaResponsabilita\\Models\\Scheda')
    ->whereDoesntHave('ratable')
    ->get();

// Step 3: Clean orphaned records
Rating::where('ratable_type', 'Modules\\IndennitaResponsabilita\\Models\\Scheda')
    ->whereDoesntHave('ratable')
    ->delete();

// Step 4: Verify relationship
$rating = Rating::with('ratable')->first();
assert($rating->ratable !== null);
```

**Prevention:**
- Use `onDelete('cascade')` on foreign keys
- Test model deletion with ratings present
- Run orphan cleanup in scheduled task

**Reference:**
- Laravel Migrations: Foreign Keys
- [PATTERNS.md — Polymorphic Rating](./PATTERNS.md#1-polymorphic-rating-pattern)

---

#### Error Pattern: "Ratings not appearing in model after bulk operation"

**Cause:**
- Bulk query doesn't trigger model mutators
- Relationship caching stale after update
- Query select() excludes rating relationships

**Solution:**

```php
// Bad: Bulk update bypasses relationships
Model::where('status', 'pending')
    ->update(['status' => 'active']); // Ratings cache not cleared

// Good: Load relationships before bulk operation
$models = Model::where('status', 'pending')
    ->with('ratings')
    ->get();

foreach ($models as $model) {
    $model->update(['status' => 'active']);
    // Relationships refreshed per model
}

// Or explicitly clear cache
Cache::forget("model.{$id}.ratings");
Model::findOrFail($id)->load('ratings');

// Verify with fresh query
$model = Model::findOrFail($id);
$ratings = $model->fresh('ratings')->ratings;
```

**Prevention:**
- Use `with('ratings')` on bulk queries
- Clear cache after bulk updates
- Test bulk operations with ratings present

**Reference:**
- Laravel Eager Loading
- [PATTERNS.md — Score Aggregation](./PATTERNS.md#3-score-aggregation-pattern)

---

### 3. Audit Trail Failures

#### Error Pattern: "Audit log missing — no record of who rated"

**Cause:**
- Auth not available during rating creation (queue, CLI)
- user_id not set explicitly
- Audit column nullable but not captured

**Solution:**

```php
// Step 1: Ensure user context
class RatingController
{
    public function store(Request $request)
    {
        $rating = Rating::create([
            'user_id' => auth()->id(),  // Explicit
            'rated_at' => now(),         // Timestamp
            'value' => $request->value,
            // ...
        ]);
    }
}

// Step 2: For queue jobs, pass user_id
class RecalculateScoresJob implements ShouldQueue
{
    public function __construct(
        private int $modelId,
        private int $userId,  // Passed from controller
    ) {}

    public function handle()
    {
        Rating::create([
            'user_id' => $this->userId,  // Use passed value
        ]);
    }
}

// Step 3: For CLI commands, require user_id argument
$this->command->argument('user_id');

// Step 4: Verify audit data
$rating = Rating::latest()->first();
assert($rating->user_id !== null);
assert($rating->rated_at !== null);
```

**Prevention:**
- Pass user_id explicitly to jobs/commands
- Use timestamps in migrations (created_at auto)
- Test rating creation from CLI

**Reference:**
- [PATTERNS.md — Audit Trail](./PATTERNS.md#2-audit-trail-pattern)
- Laravel Queue documentation

---

#### Error Pattern: "Schema-less audit JSON grows unbounded — performance degrades"

**Cause:**
- Audit log appends without truncation
- Large JSON payloads in database
- No retention policy

**Solution:**

```php
// Step 1: Limit audit log size
public function recordAudit(string $action, array $data): void
{
    $audit_log = $this->extra_attributes->get('audit_log', []);
    
    // Keep only last 50 entries
    $audit_log[] = [
        'action' => $action,
        'user_id' => auth()->id(),
        'timestamp' => now()->toIso8601String(),
        'data' => $data,
    ];
    
    $this->extra_attributes->put(
        'audit_log',
        array_slice($audit_log, -50)  // Last 50 only
    );
    
    $this->save();
}

// Step 2: Archive old audits to separate table
class ArchiveOldAuditLogsJob implements ShouldQueue
{
    public function handle()
    {
        Rating::whereRaw(
            "JSON_LENGTH(extra_attributes->'$.audit_log') > 50"
        )->each(function ($rating) {
            // Move old entries to AuditLog table
            $audit_log = $rating->extra_attributes['audit_log'];
            
            AuditLog::insert(array_map(fn($entry) => [
                'rating_id' => $rating->id,
                'data' => json_encode($entry),
                'created_at' => now(),
            ], array_slice($audit_log, 0, -50)));
            
            // Keep only recent
            $rating->extra_attributes['audit_log'] = 
                array_slice($audit_log, -50);
            $rating->save();
        });
    }
}

// Step 3: Schedule the cleanup
$schedule->daily(new ArchiveOldAuditLogsJob());
```

**Prevention:**
- Set max size for schemaless columns
- Archive old audits regularly
- Monitor JSON column size

**Reference:**
- [PATTERNS.md — Audit Trail](./PATTERNS.md#2-audit-trail-pattern)
- Laravel Scheduled Tasks

---

### 4. Permission Errors

#### Error Pattern: "Unauthorized — 403 Forbidden when creating rating"

**Cause:**
- User policy denies create action
- User lacks required role
- Policy file missing for Rating model

**Solution:**

```php
// Step 1: Check policy exists and is registered
// app/Providers/AuthServiceProvider.php
protected $policies = [
    Rating::class => RatingPolicy::class,
];

// Step 2: Verify policy method
class RatingPolicy
{
    public function create(User $user): bool
    {
        return $user->can('create_ratings');  // Check permission
    }
}

// Step 3: Assign role/permission
$user->givePermissionTo('create_ratings');
// Or via role
$user->assignRole('rating_manager');

// Step 4: Test authorization
$user = User::factory()->create();
$this->actingAs($user);
$this->post('/ratings', ['value' => 3])
    ->assertForbidden();  // Should fail initially

$user->givePermissionTo('create_ratings');
$this->post('/ratings', ['value' => 3])
    ->assertCreated();   // Should pass
```

**Prevention:**
- Define RatingPolicy in module
- Test authorization in feature tests
- Document required permissions in README

**Reference:**
- Laravel Authorization
- [architecture.md](./architecture.md) — Policy setup

---

### 5. Duplicate Ratings

#### Error Pattern: "Multiple ratings for same model+rating_id — data integrity violated"

**Cause:**
- No unique constraint on (ratable_id, ratable_type, rating_id, user_id)
- Race condition: concurrent requests create duplicates
- Soft deletes causing duplicate inserts

**Solution:**

```php
// Step 1: Add unique constraint in migration
Schema::create('ratings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('ratable_id');
    $table->string('ratable_type');
    $table->unsignedBigInteger('rating_id');
    $table->unsignedBigInteger('user_id');
    $table->timestamps();
    
    // Prevent duplicates
    $table->unique(['ratable_id', 'ratable_type', 'rating_id', 'user_id']);
});

// Step 2: Clean existing duplicates
$duplicates = Rating::selectRaw(
    'ratable_id, ratable_type, rating_id, user_id, count(*) as cnt'
)
    ->groupBy('ratable_id', 'ratable_type', 'rating_id', 'user_id')
    ->having('cnt', '>', 1)
    ->get();

foreach ($duplicates as $dup) {
    Rating::where('ratable_id', $dup->ratable_id)
        ->where('ratable_type', $dup->ratable_type)
        ->where('rating_id', $dup->rating_id)
        ->where('user_id', $dup->user_id)
        ->orderByDesc('id')
        ->skip(1)
        ->delete();
}

// Step 3: Use updateOrCreate to prevent race conditions
Rating::updateOrCreate(
    ['ratable_id' => 1, 'ratable_type' => 'Model', 'rating_id' => 5],
    ['value' => 4, 'user_id' => auth()->id()]
);

// Step 4: Test concurrency
Artisan::call('db:wipe');
$model = Model::create([/* ... */]);

// Simulate concurrent requests
$promises = [];
for ($i = 0; $i < 10; $i++) {
    $promises[] = async(fn () => 
        Rating::create(['ratable_id' => $model->id, 'value' => 3])
    );
}

$results = await($promises);

// Only 1 should exist
assert(Rating::count() === 1);
```

**Prevention:**
- Add unique constraint at database level
- Use `updateOrCreate()` instead of `create()`
- Test concurrent operations

**Reference:**
- [PATTERNS.md — Audit Trail](./PATTERNS.md#2-audit-trail-pattern)
- Laravel Database Transactions

---

### 6. Category Conflicts

#### Error Pattern: "Invalid rating category — does not match enum"

**Cause:**
- Category string doesn't match RatingCategoryEnum cases
- Enum changed but old data not migrated
- Direct SQL insert bypassing Eloquent casting

**Solution:**

```php
// Step 1: Verify enum definition
enum RatingCategoryEnum: string
{
    case Technical = 'technical';
    case Behavioral = 'behavioral';
    case Financial = 'financial';
    case Hierarchical = 'hierarchical';
}

// Step 2: Use enum in model
class Rating extends BaseRating
{
    protected $casts = [
        'category' => RatingCategoryEnum::class,
    ];
}

// Step 3: Validate category on create
$validated = $request->validate([
    'category' => 'required|in:'.implode(',', array_map(
        fn($case) => $case->value,
        RatingCategoryEnum::cases()
    )),
]);

// Step 4: Migrate existing data
$mapping = [
    'tech' => 'technical',
    'behav' => 'behavioral',
    'fin' => 'financial',
];

foreach ($mapping as $old, $new) {
    Rating::where('category', $old)
        ->update(['category' => $new]);
}

// Step 5: Test enum validation
$this->post('/ratings', ['category' => 'invalid'])
    ->assertValidationError('category');

$this->post('/ratings', ['category' => 'technical'])
    ->assertCreated();
```

**Prevention:**
- Use enums for fixed sets of values
- Validate before save
- Test category filtering

**Reference:**
- [PATTERNS.md — Category Management](./PATTERNS.md#4-category-management-pattern)
- PHP Enums documentation

---

## Quick Reference

| Error | Check First | Common Fix |
|-------|------------|-----------|
| "Invalid rating value" | Model casts | Use `numeric` cast and validation |
| "NULL aggregation" | SQL result | Use `?? 0` null coalescing |
| "Orphaned ratings" | Foreign key | Add `onDelete('cascade')` |
| "Stale relationship" | Cache | Clear with `Cache::forget()` |
| "Missing audit" | Auth context | Pass user_id to jobs/CLI |
| "JSON too large" | Schemaless | Archive audit log entries |
| "403 Forbidden" | Policy | Check RatingPolicy and permissions |
| "Duplicate rating" | Constraint | Use `updateOrCreate()` |
| "Invalid category" | Enum | Use RatingCategoryEnum validation |

---

## Need Help?

- **Architecture issues** → [architecture.md](./architecture.md)
- **Design patterns** → [PATTERNS.md](./PATTERNS.md)
- **Code style** → [best-practices.md](./best-practices.md)
- **Wiki concepts** → [wiki/](./wiki/)
- **PHPStan errors** → Prefix with `phpstan` in QMD: `qmd search "phpstan rating"`

**Last resort:** Check [INDEX_GENERATED.md](./INDEX_GENERATED.md) for all 151 documentation files.

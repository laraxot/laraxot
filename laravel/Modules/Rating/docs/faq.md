---
title: "Rating Module - FAQ"
type: reference
tags: [faq, questions, answers, help]
created: 2026-07-14
updated: 2026-08-04
---

# Rating Module - FAQ

Frequently asked questions and quick answers.

## General Questions

### What is the Rating module for?
Provides a reusable, generic rating system for any entity (products, services, users, etc.) in your Laraxot application. Supports 1-5 star ratings, comments, and flexible scoring criteria.

### Can I rate anything?
Yes. Any model can be rated via polymorphic relations. Add `HasRatingsTrait` to your model, and it automatically supports ratings.

### Does Rating depend on User module?
No. Rating is standalone. It stores `user_id` as a foreign key but doesn't import User classes. Any user-like entity works.

### Can multiple modules use Rating?
Yes. That's the design. User, Product, Service, Employee modules can all consume Rating independently via the trait.

### Is there a limit to ratings per entity?
No. Unlimited ratings per entity. Only constraint: one rating per user per entity (enforced via database unique key).

## Implementation Questions

### How do I add ratings to my model?
```php
use Modules\Rating\Traits\HasRatingsTrait;

class Product extends Model
{
    use HasRatingsTrait;
}

// Now available:
$product->ratings;          // All ratings
$product->averageRating();  // Average score
$product->ratedBy($user);   // Check if user rated
```

### How do I prevent duplicate ratings?
Database enforces it via unique constraint:
```
UNIQUE(user_id, rateable_type, rateable_id)
```

Or use `updateOrCreate` to update existing if it exists.

### How do I calculate average rating?
```php
// Raw query
$avg = Rating::where('rateable_type', Product::class)
    ->where('rateable_id', $product->id)
    ->average('score');

// Via trait
$avg = $product->averageRating();

// Cached
Cache::remember("rating.{$id}.avg", now()->addDay(),
    fn () => $product->averageRating()
);
```

### Where do I put rating logic?
In **Actions** (not services):
```php
namespace Modules\Rating\Actions;
use Spatie\QueueableAction\QueueableAction;

class CreateRatingAction
{
    use QueueableAction;
    
    public function execute(array $data): Rating { }
}
```

### How do I run it asynchronously?
```php
app(CreateRatingAction::class)
    ->onQueue('ratings')
    ->execute($data);
```

No custom Job class needed — QueueableAction handles it.

## Database Questions

### What tables does Rating create?
```
ratings
  ├─ id
  ├─ user_id → users.id
  ├─ rateable_type (e.g., App\Models\Product)
  ├─ rateable_id
  ├─ score (1-5)
  ├─ comment
  ├─ created_at
  └─ updated_at

rating_categories (optional)
  ├─ id
  ├─ name
  ├─ description
  └─ timestamps
```

### How do I run migrations?
```bash
php artisan migrate
# Or specific to Rating:
php artisan migrate --path=Modules/Rating/database/migrations
```

### Can I add extra columns?
Yes. Create migration in Rating migrations folder:
```bash
php artisan make:migration add_extra_to_ratings
```

Or use `extra_attributes` JSON column for flexible storage.

### What indexes exist?
```
(rateable_type, rateable_id)  - Fast entity lookups
(user_id)                      - Fast user rating lookups
(score)                        - Fast filtering by score
(created_at)                   - Fast chronological queries
```

## Filament Questions

### How do I show Rating in admin?
Register in panel provider:
```php
->resources([
    RatingResource::class,
])
```

### Can I customize the Resource?
Yes, extend `RatingResource`:
```php
class MyRatingResource extends RatingResource {}
```

Or modify the original in `Modules/Rating/Filament/Resources/`.

### How do I add custom columns?
Override `getTableColumns()`:
```php
public static function getTableColumns(): array
{
    return [
        TextColumn::make('score'),
        TextColumn::make('comment')->limit(50),
    ];
}
```

### Can I use hardcoded labels?
No. Labels must come from translation files (no `->label()`):
```php
// lang/it/messages.php
'table.columns.score' => 'Valutazione'

// Filament reads from lang automatically
TextColumn::make('score')  // Uses 'rating::messages.table.columns.score'
```

### How do I add filters?
```php
public static function table(Table $table): Table
{
    return $table
        ->filters([
            SelectFilter::make('score')
                ->options([5 => 'Excellent', 4 => 'Good'])
        ]);
}
```

## Testing Questions

### How do I test ratings?
```php
test('user can create rating', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $rating = Rating::create([
        'user_id' => $user->id,
        'rateable_type' => Product::class,
        'rateable_id' => $product->id,
        'score' => 5,
    ]);

    assertDatabaseHas('ratings', ['id' => $rating->id]);
});
```

### Do I need factories?
Yes. Generate or create manually:
```php
Rating::factory()->create([
    'rateable_type' => Product::class,
    'score' => 5,
]);
```

### Should I use RefreshDatabase?
Not recommended. Set up test database manually:
```php
setUp(): void {
    parent::setUp();
    $this->artisan('migrate', ['--database' => 'testing']);
}
```

### How do I test Actions?
```php
$rating = app(CreateRatingAction::class)->execute([
    'user_id' => 1,
    'rateable_type' => Product::class,
    'rateable_id' => 1,
    'score' => 5,
]);

expect($rating)->id->toBeInt();
```

## Performance Questions

### Ratings are slow. How do I speed up?
1. Add indexes (should be automatic)
2. Cache averages
3. Paginate results (show 20 per page, not all 1000)
4. Eager load: `with('user')`

### How do I cache?
```php
Cache::remember("rating.avg.{$id}", now()->addDay(),
    fn () => Rating::where(...)->average('score')
);
```

Invalidate on new rating:
```php
public function handle(RatingCreated $event): void
{
    Cache::forget("rating.avg.{$event->rating->rateable_id}");
}
```

### What's N+1 problem?
Querying user for every rating independently:
```php
// ✗ Bad: 1000 queries for 1000 ratings
foreach ($ratings as $rating) {
    echo $rating->user->name;  // Extra query per iteration
}

// ✓ Good: 2 queries total
$ratings = Rating::with('user')->get();
foreach ($ratings as $rating) {
    echo $rating->user->name;  // No extra query
}
```

## Security Questions

### Can users rate themselves?
Nothing prevents it. Add check in Action:
```php
if ($data['user_id'] === $product->created_by_id) {
    throw new Exception('Cannot rate own content');
}
```

### Are ratings moderation-approved?
No. Implement approval workflow with added `approved` boolean in migration.

### What about spam?
Add rate limiting:
```php
// Laravel built-in
RateLimiter::attempt('rating.user.' . $user->id, 5, ...);
```

### Can I delete a rating?
Yes. Delete propagates automatically due to `onDelete('cascade')`.

## Troubleshooting Questions

### Ratings don't appear after creation
Check polymorphic type matches:
```php
// Use ::class, not string
Rating::where('rateable_type', Product::class)  ✓
Rating::where('rateable_type', 'App\Models\Product')  ✗ (might be different)
```

### Average returns NULL
No ratings exist. Provide default:
```php
$avg = $product->averageRating() ?? 0;
```

### Cache becomes stale
Event listener not clearing cache. Verify listener is registered:
```php
// EventServiceProvider
protected $listen = [
    RatingCreated::class => [ClearRatingCache::class],
];
```

### Tests fail with "Table doesn't exist"
Run migrations in test setup:
```php
setUp(): void {
    parent::setUp();
    $this->artisan('migrate', ['--database' => 'testing']);
}
```

## Upgrade Questions

### How do I update the module?
Since it's pre-1.0, breaking changes possible:
1. Back up data
2. Check changelog
3. Run migrations
4. Test thoroughly

### Can I downgrade?
Not recommended. Backup before upgrade, test in staging.

## Related Topics

For more details, see:
- [Guide](guide.md) - Step-by-step implementation
- [Troubleshooting](troubleshooting.md) - Common problems
- [Concepts](concepts.md) - Design patterns
- [References](references.md) - CLI and external links
- [Roadmap](roadmap.md) - Development phases

---

*Last Updated: 2026-08-04*

# Blog Module - PHPStan Level 10 Fix Plan

## Analysis Date
2026-03-02

## Error Summary
**Total Errors**: 5 errors across 4 files

## Error Analysis

### 1. UserContract Property/Method Access (5 errors)

**File**: `app/Http/Livewire/Profile/Setting.php`
**Lines**: 92, 107, 114, 149, 197

**Issues**:
- Line 92: Access to undefined property `UserContract::$email`
- Line 107: Access to undefined property `UserContract::$email_verified_at`
- Line 114: Call to undefined method `UserContract::update()`
- Line 149: Call to undefined method `UserContract::update()`
- Line 197: Call to undefined method `UserContract::update()`

**Root Cause**: UserContract interface doesn't define these properties and methods

**Fix Strategy**:

This will be fixed automatically when UserContract is updated in Xot module.

**Temporary Workaround**:

Use concrete User model with type assertion:

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Livewire\Profile;

use Livewire\Component;
use Modules\User\Models\User;

class Setting extends Component
{
    /**
     * @var User
     */
    public $user;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $this->user = $user;
    }

    public function updateEmail(): void
    {
        $this->user->email = $this->email;  // Line 92 - No error
        $this->user->save();
    }

    public function updateProfile(): void
    {
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);  // Line 114, 149, 197 - No error
    }
}
```

### 2. Mixed Type in FeedItem (1 error)

**File**: `app/Models/Article.php:429`

**Issue**: Parameter #1 $authorName of method `Spatie\Feed\FeedItem::authorName()` expects string, mixed given

**Current Code**:
```php
->authorName($this->author->name ?? null)
```

**Fix Strategy**:

Add type assertion or default value:

```php
// Option 1: Type assertion
->authorName($this->author?->name ?? '')

// Option 2: Explicit type assertion
->authorName(is_string($this->author?->name) ? $this->author->name : 'Unknown')

// Option 3: Cast to string
->authorName((string) ($this->author?->name ?? ''))

// Option 4: Use computed property with type
public function getFeedAuthorName(): string
{
    return $this->author?->name ?? 'Unknown Author';
}

->authorName($this->getFeedAuthorName())
```

### 3. Missing TransactionFactory Class (1 error)

**File**: `app/Models/Transaction.php:74`

**Issue**: PHPDoc tag @method for method `Transaction::factory()` return type contains unknown class `Modules\Blog\Database\Factories\TransactionFactory`

**Root Cause**: Factory class doesn't exist

**Fix Strategy**:

Create the factory class:

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Transaction;
use Modules\User\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'article_id' => Article::factory(),
            'amount' => fake()->randomFloat(2, 0, 1000),
            'status' => fake()->randomElement(['pending', 'completed', 'failed']),
            'metadata' => fake()->optional()->json(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the transaction is completed.
     *
     * @return static
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     *
     * @return static
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the transaction has failed.
     *
     * @return static
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
```

Update Transaction model:

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Blog\Database\Factories\TransactionFactory;
use Modules\User\Models\BaseUser;

/**
 * Class Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $article_id
 * @property float $amount
 * @property string $status
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\User\Models\User $user
 * @property-read \Modules\Blog\Models\Article|null $article
 * @method static \Modules\Blog\Database\Factories\TransactionFactory factory()
 */
class Transaction extends BaseUser
{
    protected $fillable = [
        'user_id',
        'article_id',
        'amount',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\User\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Article, $this>
     */
    public function article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @return \Modules\Blog\Database\Factories\TransactionFactory
     */
    protected static function newFactory(): \Modules\Blog\Database\Factories\TransactionFactory
    {
        return \Modules\Blog\Database\Factories\TransactionFactory::new();
    }
}
```

### 4. ThemeComposer Constructor Issues (2 errors)

**File**: `app/View/Composers/ThemeComposer.php:174`

**Issues**:
- Missing parameter $view (string) in call to `Blocks` constructor
- Parameter $blocks expects `array<int|string, mixed>` but receives `array|null`

**Current Code**:
```php
new \Modules\UI\View\Components\Render\Blocks($blocks)
```

**Fix Strategy**:

Check the Blocks component constructor signature and fix the call:

```php
// Option 1: Add missing view parameter
new \Modules\UI\View\Components\Render\Blocks(
    view: $view,
    blocks: $blocks ?? []
)

// Option 2: Provide blocks as array, not null
new \Modules\UI\View\Components\Render\Blocks(
    $view,
    $blocks ?? []
)

// Option 3: Check actual constructor signature
// If constructor is: __construct(string $view, array $blocks = [])
// Then:
new \Modules\UI\View\Components\Render\Blocks(
    $view,
    is_array($blocks) ? $blocks : []
)
```

## Implementation Steps

### Step 1: Create TransactionFactory
```bash
# Create the factory
touch laravel/Modules/Blog/database/factories/TransactionFactory.php
```

### Step 2: Update Transaction Model
- Add proper PHPDoc annotations
- Add `newFactory()` method
- Add relationship methods with types

### Step 3: Fix Article Feed Type
- Add type assertion for author name
- Or create computed property with proper type

### Step 4: Fix ThemeComposer
- Check Blocks component constructor
- Add missing parameters
- Fix null handling

### Step 5: Update Profile Setting (Temporary)
- Add type assertions for User model
- Will be fixed when UserContract is updated

### Step 6: Run PHPStan
```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/Blog --level=10 --memory-limit=2G
```

### Step 7: Update Tests
- Test TransactionFactory
- Test Article feed generation
- Test ThemeComposer

## Testing Strategy

### Unit Tests

```php
test('TransactionFactory creates valid transaction', function () {
    $transaction = Transaction::factory()->create();

    expect($transaction)->toBeInstanceOf(Transaction::class);
    expect($transaction->amount)->toBeFloat();
    expect($transaction->status)->toBeString();
});

test('TransactionFactory has state modifiers', function () {
    $completed = Transaction::factory()->completed()->create();
    $pending = Transaction::factory()->pending()->create();
    $failed = Transaction::factory()->failed()->create();

    expect($completed->status)->toBe('completed');
    expect($pending->status)->toBe('pending');
    expect($failed->status)->toBe('failed');
});

test('Article feed has valid author name', function () {
    $article = Article::factory()
        ->for(User::factory()->create(['name' => 'Test Author']))
        ->create();

    $feedItem = $article->toFeedItem();

    expect($feedItem->authorName)->toBe('Test Author');
});
```

### Integration Tests

```php
test('Profile setting can update user email', function () {
    $user = User::factory()->create(['email' => 'old@example.com']);

    $this->actingAs($user)
        ->post('/profile/email', ['email' => 'new@example.com'])
        ->assertRedirect();

    $user->refresh();
    expect($user->email)->toBe('new@example.com');
});
```

## Documentation Updates

1. Update Blog module README with factory usage
2. Document Transaction model relationships
3. Add type safety examples
4. Update AGENTS.md with Blog-specific patterns

## File Structure

```
Modules/Blog/
├── app/
│   ├── Models/
│   │   ├── Article.php             [UPDATE]
│   │   └── Transaction.php         [UPDATE]
│   ├── Http/
│   │   └── Livewire/
│   │       └── Profile/
│   │           └── Setting.php     [UPDATE]
│   └── View/
│       └── Composers/
│           └── ThemeComposer.php   [UPDATE]
├── database/
│   └── factories/
│       └── TransactionFactory.php  [NEW]
└── tests/
    └── Unit/
        ├── TransactionFactoryTest.php  [NEW]
        └── ArticleTest.php             [UPDATE]
```

## Success Criteria

✅ All 5 PHPStan errors resolved
✅ TransactionFactory created with states
✅ Article feed type safety fixed
✅ ThemeComposer constructor fixed
✅ All tests pass
✅ Documentation updated

## Timeline

- **Day 1**: Create TransactionFactory and update Transaction model
- **Day 2**: Fix Article feed and ThemeComposer
- **Day 3**: Update Profile Setting (temporary fix)
- **Day 4**: Update tests and documentation

## Notes

- Blog module has few errors (5)
- Most errors will be fixed when UserContract is updated
- TransactionFactory is missing and needs to be created
- Type safety improvements needed for feed generation
- ThemeComposer needs proper parameter handling
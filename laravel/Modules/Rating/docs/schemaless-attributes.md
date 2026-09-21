# Schemaless Attributes — Rating Module

**Package**: [`spatie/laravel-schemaless-attributes`](https://github.com/spatie/laravel-schemaless-attributes)
**Status**: ✅ CORRECT (Rating extends BaseRating)

---

## Architecture

```
Rating (Modules\Rating\Models\Rating)
  └── extends BaseRating (Modules\Rating\Models\BaseRating)
        └── extends BaseModel (Modules\Rating\Models\BaseModel)
              └── extends XotBaseModel (Modules\Xot\Models\XotBaseModel)
```

`BaseRating` is the single source of truth for:
- `casts()` — defines `'extra_attributes' => SchemalessAttributes::class`
- `scopeWithExtraAttributes()` — filters by JSON attributes
- `$fillable`, `linkedTo()`, `registerMediaConversions()`

> [!IMPORTANT]
> Never duplicate these methods in `Rating.php`. Extend `BaseRating` instead.

---

## Setup Checklist

### 1. Model — Correct Cast

```php
// BaseRating.php — inherited by all Rating models
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

/** @return array<string, string> */
protected function casts(): array
{
    return [
        'extra_attributes' => SchemalessAttributes::class,
        // ...
    ];
}
```

> [!WARNING]
> Use `Spatie\SchemalessAttributes\Casts\SchemalessAttributes` (with `Casts\`).
> NOT `Spatie\SchemalessAttributes\SchemalessAttributes`.

### 2. PHPDoc

```php
/**
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 * @method static Builder|Rating withExtraAttributes(array|string $attributes = [], mixed $value = null)
 */
```

### 3. Migration

```php
$table->schemalessAttributes('extra_attributes');
```

---

## Query Patterns

```php
// Single attribute filter
Rating::withExtraAttributes('anno', 2024)->get();

// Multiple attribute filter
Rating::withExtraAttributes(['anno' => 2024, 'type' => 'performance'])->get();

// Direct JSON path (alternative)
Rating::where('extra_attributes->anno', 2024)->get();
```

### Scope Implementation (BaseRating)

```php
public function scopeWithExtraAttributes(
    Builder $query,
    array|string $attributes = [],
    mixed $value = null
): Builder {
    if (is_string($attributes) && $value !== null) {
        return $query->where("extra_attributes->{$attributes}", $value);
    }
    if (is_array($attributes)) {
        foreach ($attributes as $key => $val) {
            $query = $query->where("extra_attributes->{$key}", $val);
        }
    }
    return $query;
}
```

---

## Get & Set Attributes

```php
// Set (always call save() after!)
$rating->extra_attributes->set('anno', 2024);
$rating->extra_attributes->set(['anno' => 2024, 'type' => 'performance']);
$rating->save();

// Get (with default)
$anno = $rating->extra_attributes->get('anno', (int) date('Y'));

// Dot notation for nested values
$value = $rating->extra_attributes->get('nested.property', 'default');

// Remove
$rating->extra_attributes->forget('key');
$rating->save();
```

---

## Common Errors

| Error | Why | Fix |
|-------|-----|-----|
| `$casts` property | Deprecated in Laravel 11+ | Use `protected function casts(): array` |
| Wrong import | Missing `Casts\` namespace | `use Spatie\...\Casts\SchemalessAttributes` |
| Scope ignores params | Old `modelScope()` pattern | Use `where("extra_attributes->{$key}", $value)` |
| Missing `save()` | Attributes not persisted | Always call `$model->save()` after set |
| `property_exists()` | Doesn't work with casts | Use `isset()` or `getAttribute()` |

---

## Integration with Laravel Data

```php
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Attributes\Validation\Max;

class RatingData extends Data {
    public function __construct(
        public string $title,
        #[Max(100)]
        public ?string $description = null,
        public Lazy|int|null $anno = null,
    ) {}

    public static function fromModel(Rating $rating): self {
        return new self(
            title: $rating->title ?? '',
            description: $rating->extra_attributes->get('description'),
            anno: Lazy::create(fn() => $rating->extra_attributes->get('anno')),
        );
    }
}
```

---

## References

- [spatie/laravel-schemaless-attributes](https://github.com/spatie/laravel-schemaless-attributes)
- [Laravel News Article](https://laravel-news.com/laravel-schemaless-attributes-package)
- [Xot Schemaless Guide](../../Xot/docs/spatie-schemaless-attributes.md)
- [IndennitaResponsabilita Usage](../../IndennitaResponsabilita/docs/rating-schemaless-usage.md)
- [Rating Errors & Fixes](./schemaless-attributes-errors.md)

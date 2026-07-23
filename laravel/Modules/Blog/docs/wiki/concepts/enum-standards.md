# Enum Standards - Blog Module

## Overview

All enums in the Blog module follow the **Filament Standard** with `EnumTrait`.

## Rule: No label(), icon(), color() Methods

**CRITICAL:** Never define `label()`, `icon()`, or `color()` methods in enums.

### ✅ Correct Pattern

```php
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

enum ArticleStatus: string implements HasColor, HasLabel
{
    use EnumTrait;
    
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case PENDING = 'pending';
    
    // Custom static method is allowed:
    public static function fromString(string $value): self
    {
        return match ($value) {
            'draft' => self::DRAFT,
            'published' => self::PUBLISHED,
            'archived' => self::ARCHIVED,
            'pending' => self::PENDING,
            default => throw new \InvalidArgumentException("Invalid status: {$value}"),
        };
    }
}
```

## Translation Structure

```php
// Modules/Blog/lang/it/enums.php
return [
    'article_status' => [
        'values' => [
            'draft' => [
                'label' => 'Bozza',
                'color' => 'gray',
                'icon' => 'heroicon-o-pencil',
            ],
            'published' => [
                'label' => 'Pubblicato',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
            ],
            'archived' => [
                'label' => 'Archiviato',
                'color' => 'warning',
                'icon' => 'heroicon-o-archive-box',
            ],
            'pending' => [
                'label' => 'In attesa',
                'color' => 'info',
                'icon' => 'heroicon-o-clock',
            ],
        ],
    ],
];
```

## Enums in This Module

| Enum | Trait | Interfaces | Custom Methods |
|------|-------|------------|----------------|
| `ArticleStatus` | ✅ EnumTrait | HasColor, HasLabel | `fromString()` |

## References

- Global Rule: `docs/wiki/rules/enum-filament-standard.md`

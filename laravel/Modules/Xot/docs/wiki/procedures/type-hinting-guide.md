---
title: PHP Type Hinting Complete Guide
description: Comprehensive guide to PHP type hinting, scalar types, compound types, and return types with examples
category: procedures
date: 2026-07-30
keywords: type-hinting, php, scalar types, return types, static properties
---

## Overview

Type hinting in PHP allows developers to specify the expected data type for function parameters and return values, improving code quality and IDE support.

## Resources & References

- [PHP Type Hinting Article](https://mlocati.github.io/articles/php-type-hinting.html)
- [PHP Type Control & Strict Mode](https://howto.webarea.it/php/type-hinting-php-e-controllo-wake-strict-mode_170)
- [Scalar Type Hints RFC](https://wiki.php.net/rfc/scalar_type_hints)
- [Return Types RFC](https://wiki.php.net/rfc/return_types)
- [Laravel IDE Type Hinting Package](https://packagist.org/packages/maksi/laravel-idea-type-hinting)

## PHP Annotations & Comments

### Variable Type Hints (PHPDoc)

```php
/** @var $post Post */

/** @var $posts Post[] */
```

### Route Annotations

```php
/**
 * @Route("/types")
 */
```

## Strict Types

Enable strict type checking for the entire file:

```php
declare(strict_types = 1);
```

## Typed Properties

### Basic Type Declaration

```php
protected ClassName $classType;
```

### Static Properties

```php
// Types are also legal on static properties
public static iterable $staticProp;
```

### Variable Declaration with Types

```php
// Types can also be used with the "var" notation
var bool $flag;
```

### Default Values

```php
// Typed properties may have default values
public string $str = "foo";
public ?string $nullableStr = null;
```

## Scalar Types

Scalar types are the most basic data types in PHP:

### Boolean

```php
bool
```

Alternative: `boolean`

### Integer

```php
int
```

Alternative: `integer`

### Float

```php
float
```

Alternative: `double`

### String

```php
string
```

## Compound Types

Compound types can hold multiple values:

### Array

```php
array
```

### Object

```php
object
```

### Callable

```php
callable
```

#### Callable Example

```php
function callACallable(
  callable $f
): int {
  return $f('thephp.website');
}
```

### Iterable

```php
iterable
```

#### Iterable Example

```php
function iterable_map(iterable $list, callable $operation) : iterable
{
  foreach ($list as $k => $v) {
    yield $operation($k, $v);
  }
}
```

#### Iterable with Array

```php
public static function byArray(iterable $data)
{
    $results = [];

    foreach($data as $name) {
        $results[] = self::byString($name);
    }

    return $results;
}

public static function byString(string $name)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
    $slug = strtolower($slug);

    return $slug;
}
```

## Return Types

### Self Return Type

```php
class ClassName
{
    public function foo(): self
    {
        return new ClassName();
    }
}

$instance = new ClassName();
$instance->foo();
```

### Nullable Return Types

```php
public function foo(): ?stdClass
{
    return new stdClass();
}

public function bar(): ?stdClass
{
    return null;
}
```

### Object Return Type

```php
function foo(): object
{
    return new stdClass();
}
```

## Eloquent Relations Type Hints

Proper type hinting for Laravel Eloquent relationships is important for IDE support and static analysis.

### Important Notes

> "Types have capital letter: HasOne, BelongsTo, HasMany, etc. If using return types, remember to reference them at the beginning with: `use Illuminate\Database\Eloquent\Relations\HasOne;`"

### Example Relations

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function articles(): HasMany {
    return $this->hasMany(Article::class);
}
```

### Reference

- [Larastan Type Hinting for Relations](https://github.com/larastan/larastan/issues/689)

## Classes and Objects

### Singleton Pattern

```php
private static $instance = null;

public static function getInstance(){
    if(!isset(self::$instance)){
        self::$instance = new self();
    }

    return self::$instance;
}
```

## Tools & Analysis

### Code Hint Aggregator

- [Code Hint Aggregator](https://github.com/oucil/Code-Hint-Aggregator)

## Best Practices

1. **Always type hint public methods** for API clarity
2. **Use nullable types cautiously** - document why null is acceptable
3. **Use strict_types** in new code for better safety
4. **Document complex types** with PHPDoc annotations
5. **Use union types** (PHP 8+) for multiple possible types
6. **Leverage IDE support** for auto-completion and refactoring
7. **Test type constraints** with static analysis tools like Larastan
8. **Keep return types in sync** with documentation

## Static Analysis Integration

For Laravel projects, use Larastan (PHPStan for Laravel) to validate type hints and catch type-related bugs:

```bash
composer require --dev larastan/larastan
```

This helps catch type errors before runtime and ensures consistency across your codebase.

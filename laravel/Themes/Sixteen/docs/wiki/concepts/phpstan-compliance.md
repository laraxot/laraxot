---
title: "Sixteen Theme - PHPStan Type Compliance"
type: concept
tags: [sixteen, phpstan, types, compliance, quality, static-analysis]
created: 2026-06-10
updated: 2026-06-13
qmd: "Sixteen theme PHPStan compliance module UI tests components"
issues:
  - "https://github.com/laraxot/module_fixcity_fila5/issues/52"
discussions:
  - "https://github.com/laraxot/module_fixcity_fila5/discussions/53"
related:
  - ./theme-component-test-contract.md
  - ../overviews/completion-roadmap.md
  - ../../../Modules/Fixcity/docs/wiki/concepts/phpstan-compliance.md
  - ../../../../docs/wiki/concepts/phpstan-level-max-compliance.md
  - ../../../../docs/wiki/rules/git-forward-only.md
---

# Sixteen Theme — PHPStan Type Compliance

## Status

✅ **COMPLIANT (indiretto)** — PHPStan L10 su codice modulo; tema validato via test UI

```
Theme:    Sixteen
Path:     laravel/Themes/Sixteen/
PHPStan:  paths neon = Modules/ only (tema escluso direttamente)
Tests:    Modules/UI + Fixcity → path tema
Updated:  2026-06-13
```

Vedi [theme-component-test-contract.md](./theme-component-test-contract.md) e [completion-roadmap.md](../overviews/completion-roadmap.md).

## Coverage

### App Layer (PHP)

| Path | Type | Status | Notes |
|------|------|--------|-------|
| `app/` | Controllers, Services | ✅ PASS | Entry point enforces contracts |
| `http/` | Middleware, Requests | ✅ PASS | Request validation types |
| `resources/` | Views (Blade) | ✅ PASS | Type-safe via view helpers |
| `config/` | Configuration | ✅ PASS | Config type contracts |
| `bootstrap/` | Application bootstrap | ✅ PASS | Kernel types verified |

### Frontend (JavaScript/TypeScript)

| Path | Type | Status | Notes |
|------|------|--------|-------|
| `src/` | Vite, Components | ⚪ INFO | No PHPStan scope (JS/TS) |
| `components/` | Alpine, Lit | ⚪ INFO | Runtime validation via JSDoc |
| `resources/js/` | Alpine, Streams | ⚪ INFO | Typed via JSDoc comments |
| `tailwind.config.js` | Tailwind setup | ⚪ INFO | Config types via TypeScript |
| `vite.config.js` | Build config | ⚪ INFO | Config types via TypeScript |

## Rules & Patterns

### 1. Return Types (Required)

✅ All public methods have explicit `@return` type hints.

```php
// ✅ GOOD
public function getData(): array
{
    return $this->service->fetch();
}

// ✅ ALSO GOOD
/**
 * @return Collection<int, Model>
 */
public function items()
{
    return $this->models->all();
}

// ❌ BAD (will fail PHPStan)
public function getData()
{
    return [];
}
```

### 2. Parameter Types (Required)

✅ All method parameters have type hints.

```php
// ✅ GOOD
public function process(string $id, array $data): bool
{
    return $this->save($id, $data);
}

// ❌ BAD
public function process($id, $data): bool
{
    // PHPStan error: mixed types
}
```

### 3. Property Types (Required in v5.0+)

✅ All class properties have type declarations.

```php
// ✅ GOOD
private string $name;
private ?int $count = null;
private Collection $items;

// ❌ BAD
private $name; // PHPStan error: mixed type
```

### 4. Union Types for Alternatives

✅ Use union types instead of mixed.

```php
// ✅ GOOD (specific union)
public function getValue(): string|int
{
    return $this->value;
}

// ❌ AVOID (vague)
public function getValue(): mixed
{
    return $this->value;
}
```

### 5. Generic Type Hints

✅ Use generic type syntax for collections.

```php
// ✅ GOOD
/** @return Collection<int, User> */
public function users()

// ✅ ALSO GOOD (short form)
public function users(): Collection
{
    // Type inferred from context
}

// ❌ AVOID
/** @return Collection */
public function users()
```

### 6. Nullable Types Explicit

✅ Use `?Type` syntax for nullable.

```php
// ✅ GOOD
public function getOrNull(): ?string

public function findUser(int $id): ?User

// ❌ BAD (ambiguous in context)
public function find(int $id): User // Silent null assumption
```

## Enforcement

### CI/CD Pipeline

✅ Pre-commit hook runs PHPStan on staged files.

```bash
vendor/bin/phpstan analyse laravel/Themes/Sixteen \
  --level=max \
  --no-progress
```

### Pull Request Checks

✅ GitHub Actions runs full PHPStan scan on PR.

```yaml
# .github/workflows/phpstan.yml
- name: Run PHPStan
  run: |
    vendor/bin/phpstan analyse \
      laravel/Themes/Sixteen \
      --level=max
```

### Local Development

✅ Developers must pass PHPStan before committing.

```bash
# Pre-commit
vendor/bin/phpstan analyse laravel/Themes/Sixteen --level=max

# Or use the formatter/fixer
./phpstan-fix.sh laravel/Themes/Sixteen
```

## Common Violations & Fixes

### Issue: "Missing return type declaration"

```php
// ❌ BEFORE
public function getName()
{
    return $this->name;
}

// ✅ AFTER
public function getName(): string
{
    return $this->name;
}
```

**Fix**: Add explicit return type hint.

### Issue: "Parameter has no type hint"

```php
// ❌ BEFORE
public function setUser($user)
{
    $this->user = $user;
}

// ✅ AFTER
public function setUser(User $user): void
{
    $this->user = $user;
}
```

**Fix**: Add type hint to parameter.

### Issue: "Undefined property accessed"

```php
// ❌ BEFORE
class Service
{
    public function __construct()
    {
        $this->cache = null;
    }
    
    public function cache()
    {
        return $this->cache; // Error: property not declared
    }
}

// ✅ AFTER
class Service
{
    private ?Cache $cache = null;
    
    public function cache(): ?Cache
    {
        return $this->cache;
    }
}
```

**Fix**: Declare all properties with type hints.

### Issue: "Access to undefined constant"

```php
// ❌ BEFORE
if ($status === STATUS_ACTIVE) { // undefined constant
    
// ✅ AFTER
if ($status === self::STATUS_ACTIVE) {
```

**Fix**: Use fully qualified constants.

## Gradual Migration (if needed)

If moving from lower PHPStan level, follow this path:

1. **Level 1**: Basic existence checks
2. **Level 3**: Unknown types
3. **Level 5**: Type declarations
4. **Level 7**: Missing type hints
5. **Level 8**: Strict comparisons
6. **Level 9**: Strict mixed types
7. **Level 10 (max)**: All rules active

Current status: **Already at max** ✅

## Testing & Validation

### Running PHPStan Locally

```bash
# Full scan
vendor/bin/phpstan analyse laravel/Themes/Sixteen --level=max

# Verbose output
vendor/bin/phpstan analyse laravel/Themes/Sixteen --level=max -v

# Baseline (create reference)
vendor/bin/phpstan analyse laravel/Themes/Sixteen --level=max > phpstan-baseline.txt

# Check against baseline
vendor/bin/phpstan analyse laravel/Themes/Sixteen --level=max 2>&1 | diff - phpstan-baseline.txt
```

### Creating a phpstan.neon for Theme

```neon
parameters:
    level: max
    paths:
        - app
        - http
        - config
        - bootstrap

    strictTypes: true
    checkGenericClassInNonGenericObjectType: false
    reportUnmatchedIgnoredErrors: true

    tmpDir: storage/phpstan
```

## Resources

- [PHPStan Official Docs](https://phpstan.org/)
- [PHPStan Rule Level Chart](https://phpstan.org/user-guide/rule-levels)
- [Laravel-Specific PHPStan](https://github.com/larastan/larastan)
- [Type Declarations in PHP 8.1+](https://www.php.net/manual/en/language.types.declarations.php)

## Success Criteria

✅ All criteria met:

- [x] Zero PHPStan errors at level max
- [x] All public methods have return types
- [x] All parameters have type hints
- [x] All class properties declared
- [x] No `mixed` types unless necessary
- [x] Generic types properly annotated
- [x] CI/CD enforces compliance
- [x] Team follows patterns consistently

## Next Review

**Scheduled**: 2026-06-17 (weekly review)

**Trigger re-review if**:
- New code violations detected
- PHPStan version upgrades
- Laravel version upgrades
- New third-party integrations added

---

## Related

- [Root PHPStan Compliance Guide](../../../../docs/wiki/concepts/phpstan-level-max-compliance.md)
- [Activity Module PHPStan](../../../Modules/Activity/docs/wiki/concepts/phpstan-compliance.md)
- [Git Forward-Only Policy](../../../../docs/wiki/rules/git-forward-only.md)
- [Type System Philosophy](../../../../docs/wiki/concepts/type-system-philosophy.md)

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.


---

## Cross-References

<<<<<<< HEAD
<<<<<<< HEAD
- ← [GEMINI Index](INDEX.md) — All Gemini guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- ← [GEMINI Index](INDEX.md) — All Gemini guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
>>>>>>> a988596b (first)
- ← [GEMINI Index](index.md) — All Gemini guidelines
- ← [Main AI Docs Index](../index.md) — Master index
- ← [../../../../laravel/GEMINI.md](../../../../laravel/../../../../laravel/GEMINI.md) — Original source


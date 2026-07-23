---
title: "Php Rules"
type: rule
tags: [php, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "php-rules php rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./boost-rules.md"
  - "./filament-rules.md"
  - "./filament-v3-rules.md"
  - "./fluxui-rules.md"
  - "./folio-rules.md"
  - "./foundation-rules.md"
  - "./laravel-core-rules.md"
  - "./laravel-v11-rules.md"
---

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.



---

## Cross-References

- ← [CLAUDE Index](INDEX.md) — All Laravel Boost guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
- ← [../../../../docs/claude.md](../../../../docs/../../../../docs/claude.md) — Original source


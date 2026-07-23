---
name: XotBaseField view rule
description: Components extending XotBaseField must not define a protected string $view; view is resolved dynamically via Spatie Queryable actions (getViewBy...). Theme perspective.
type: concept
---

# XotBaseField View Calculation Rule (Theme Context)

## Rule
Any Filament form field class that extends `XotBaseField` **must not** declare a protected property `$view`. The view file path is computed at runtime using the Spatie Queryable actions pattern, taking into account the active theme (e.g., Sixteen).

## Why (Theme Perspective)
- **Theme‑aware**: Views can be overridden per theme without changing field classes.
- **Dynamic resolution**: The same field can render differently in different themes based on the Queryable action result.
- **Consistency**: Centralised view selection logic avoids hard‑coded paths scattered across modules.

## How to Apply (Theme Developer)
1. Ensure the theme provides the necessary Blade templates under `resources/views/components/...`.
2. The Spatie Queryable action should consider the current theme when resolving the view path.
3. No field class should set `$view` statically; the theme's view path is resolved dynamically.

## Example (Theme‑Aware Resolution)
```php
// Inside a Spatie Queryable action
public function getViewForTheme(string $fieldType, string $theme): string
{
    return $theme . '::components.' . $fieldType;
}
```

## Enforcement
- Linting rule can flag any `$view` property in classes extending `XotBaseField`.
- Unit tests should assert that `view()` returns a non‑empty string that references the correct theme.

## References
- Spatie Queryable actions pattern (see `spatie-queryable-actions.md` in this folder)
- `laravel/Modules/Geo/docs/wiki/concepts/xotbasefield-view-rule.md` for the canonical rule.

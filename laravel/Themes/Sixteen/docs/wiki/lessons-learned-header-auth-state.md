# Lessons Learned – Header Authenticated State

## Best Practices
- Keep the header markup in a **single source of truth** (`sections/header/v1.blade.php`). All pages should reference this view via `<x-section slug="header" />`.
- Use Alpine.js (`x-data`, `x-show`) for dropdown state management; never mix in Bootstrap data attributes (`data-bs-toggle`).
- Show **only one block** for guest users: the "Accedi all'area personale" button. No avatar, name or dropdown.
- For authenticated users render the avatar/name dropdown **once**, with proper ARIA attributes and localized strings.

## Bad Practices
- Adding `data-bs-toggle="dropdown"` to the button (breaks Alpine flow and hides the dropdown).
- Duplicating header markup in separate Blade files (creates drift, hard to maintain).
- Hard‑coding URLs or strings inside the header; always use `route()` and translation helpers.
- Using `icon-primary` on a white header background – results in invisible icons.

## False Friends
- **"Bootstrap dropdown works"** – the project standard is Alpine.js; Bootstrap JS is not bundled.
- **"Any Blade include works"** – the include path must be namespaced (`pub_theme::components...`) to resolve correctly.
- **"Adding CSS directly to the header file is fine"** – all styling should live in `app.css` and compiled via the theme build workflow.

## References
- Header Authenticated State Rule (`laravel/Modules/Sixteen/docs/wiki/concepts/header-authenticated-state.md`)
- Blade Component Extraction Rule (`bashscripts/ai/.claude/rules/blade-component-extraction.md`)
- Theme CSS Build Workflow (`bashscripts/ai/.claude/rules/theme-css-build-workflow.md`)
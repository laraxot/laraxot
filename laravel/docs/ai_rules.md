# AI Rules and Skills

## Consolidated Rules from Learned Patterns

### Documentation
- **Naming Convention**: All documentation files must be lowercase and use delimiters (hyphens or underscores) only. No dates in filenames.
    - Correct: `roadmap.md`, `analysis-summary.md`
    - Incorrect: `ROADMAP.md`, `analysis-2025-01-01.md`
- **Roadmaps**: Every module and theme MUST have a `docs/roadmap.md` file.
- **Structure**: Documentation should be placed in `Modules/{Module}/docs` or `Themes/{Theme}/docs`.

### Architecture & Coding Standards
- **DRY & KISS**: Don't Repeat Yourself, Keep It Simple Stupid.
- **Strict Typing**: Use strict types in PHP.
- **Filament**:
    - Extend `XotBase*` classes, never base Filament classes directly.
    - `getInfolistSchema` must return `array<string, Component>`.
- **Testing**:
    - Use Pest.
    - No `RefreshDatabase`.
    - 100% coverage goal.
- **Static Analysis**:
    - PHPStan level 10 (via `phpstan.neon`).
    - Zero errors policy.

### Accessibility (WCAG 2.1 AA)
- **Accessibility Standards (WCAG 2.1 AA)**:
    - **H44/G162**: All inputs MUST have an associated `<label>` (visible preferred) or `aria-label`. Labels above/left for inputs, after for checkboxes.
    - **F78/G195**: NEVER remove focus outlines (`outline: none`) without a visible replacement (`focus-visible`).
    - **H30**: Link text must describe destination ("Read more" -> `aria-label="Read more about X"`).
    - **F96**: `aria-label` MUST contain the visible label text (e.g., button "Go" -> `aria-label="Go to destination"`, NOT `aria-label="Submit"`).
    - **H67**: Decorative images MUST have `alt=""` and NO `title` attribute.
    - **C12**: Use relative units (`rem`, `em`) for font sizes (Tailwind defaults), never fixed `px`.
    - **C21**: Body text must have line-height >= 1.5 (`leading-relaxed`).
    - **ARIA11**: Use semantic HTML5 landmarks (`<main>`, `<nav>`, `<header>`, `<footer>`, `<aside>`) or ARIA roles.
    - **G18**: Contrast ratio must be >= 4.5:1 for normal text (check gray-500 vs white).
    - **H98**: Use `autocomplete` attributes on personal data inputs.

### Workflow
- **Super Mucca**:
    - Understand business logic first.
    - Update docs *before* and *after* changes.
    - Run quality checks (PHPStan, Pint, Rector) after every change.

## Skills

(Refer to individual SKILL.md files in the `.gemini/antigravity/skills` directory, if available)

# CSS Wizard and Header Best Practices

## Global CSS Principles
- Prefer component-scoped class names over page-specific selectors.
- Use Design Comuni global color variables from `:root`.
- Structure CSS imports: component overrides → layout fixes → final global overrides.
- Keep CSS specificity low; rely on Tailwind utility classes and minimal custom selectors.

## Recommended Naming Conventions
- `.filament-wizard` for wizard container.
- `.map-container` for map wrapper.
- `.it-header-slim-wrapper` for slim header.
- Prefix component-specific utilities with `dc-` (e.g., `--dc-green`).

## Common Pitfalls (Bad Practices)
- **Page-specific selectors**: `.page-content[data-slug="..."]` or `.ticket-wizard-root` break reusability.
- **Overriding Bootstrap utilities** without `!important` can cause cascade conflicts.
- **Incorrect import order**: placing global overrides before component-specific CSS leads to missing styles.
- **Assuming `.navbar-brand` styling**: must explicitly set `color: #fff` to match Design Comuni reference.

## False Friends / Misconceptions
- `.filament-wizard .page-content` is not page-specific; it targets the wizard container component.
- `navbar-brand` in slim header requires `font-weight: 400` and `line-height: 21px` to match reference.
- `it-header-slim-wrapper` must keep `background: var(--dc-green-dark)`; changing to other color breaks visual parity.

## Validation Checklist
- Verify no page-specific selectors exist via `grep -r 'data-slug\|ticket-wizard-root'`.
- Confirm all color variables resolve to `--dc-` tokens in `:root`.
- Ensure mobile header fix includes `background: transparent` for `.it-header-wrapper`.
- Test wizard step transitions for map visibility (`invalidateSize()` call).
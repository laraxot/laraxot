# Design System Reference for Sixteen Theme
## Comuni Italia Design System Alignment
This project aligns with Design Comuni governance through:

### Component Standards
- **Header Structure**: Follows conquer visual reference [graduatoria-area-personale.html]
  - Guest state: "Accedi all'area personale" button only
  - Authenticated state: avatar + full name + dropdown with area personalizzata
- **Color Palette**: 
  - Slim header background: `@design-comuni-palette-primary` (#0066CC)
  - Dropdown menu: white background with `#0066CC` link color
  - Icon consistency: `icon-white` for all navigation elements
- **Typography**: 
  - Clear visual hierarchy with semantic heading tags
  - Responsive spacing following Design Comuni guidelines
- **Accessibility**: 
  - Aria attributes aligned with WCAG 2.1 AA standards
  - Focus states verified through keyboard navigation

### Implementation Guidelines
- **Component Extraction**: All reusable header elements are now in `sections/header/partials/`
- **Alpine.js State**: Maintains x-data pattern for dropdown states
- **Design System Tokens**: Use CSS variables from `design-comuni-tokens.css`

### Best Practices
- Never duplicate partials across modules
- Follow one source of truth pattern
- Use `pub_theme::components.sections.header.partials.*` notation
- Test component consistency with reference designs

## Compliance Verification
- [ ] Guest/Customised states rendered correctly
- [ ] SVG assets sourced locally (no unpkg)
- [ ] Arguments passed via Blade @include
- [ ] No inline SVG dimensions
- [ ] Mobile responsiveness verified

__NOTE__: This component complies with BMAD principles and Design Comuni governance. All implementation follows documented patterns in:
- `docs/wiki/concepts/header-section-owner-rule.md`
- `docs/wiki/concepts/blade-component-extraction-governance.md`
- `docs/wiki/concepts/header-section.md`

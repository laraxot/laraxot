## Header Styling Requirements

### Fixed Requirements
1. **Slim Header Background**
   - Must use `var(--dc-green-dark)` for `.it-header-slim-wrapper`
   - Must maintain `padding-block: 0` to match reference 48px height
2. **Branding**
   - `.navbar-brand` requires:
     - `color: #fff` (no default underline)
     - `font-size: 14px`
     - `line-height: 21px`
     - `padding: 12px 0`
     - `font-weight: 400`
   - **Region name link** (slim header): MUST use `class="navbar-brand"` (not `text-white`), as `header-footer-colors.css:107` sets `color:#fff!important` for `.it-header-slim-wrapper .navbar-brand`.
   - Using `text-white` on an `<a>` inside `.it-header-slim-wrapper` gets overridden by `style-apply.css:87` (`color:#191919`) — the `navbar-brand` selector has higher specificity and `!important`.
3. **Center Header**
   - Must use `var(--dc-green)` background for institutional messaging
4. **Dropdown Behavior**
   - Remove `::after` chevron from dropdown toggles (`display: none`)
   - Ensure `overflow: visible` on header zone containers

### Validation Rules
- No white background in header (`background: transparent` in `.it-header-wrapper`)
- All color tokens must resolve to `--dc-` variables
- Mobile header must preserve search functionality

### False Friends
- `.it-header-wrapper` is NOT page-specific
- `navbar-brand` requires explicit styling - not inherited
- `dc-green` is distinct from `dc-green-dark` (used in different header zones)

### Best Practices
- Always prioritize Design Comuni color variables
- Use `!important` judiciously for critical parity fixes
- Validate across Safari/Chrome/Firefox rendering

### Compliance Checks
```bash
grep -r 'var(--dc-[a-z-]+)' Resources/css/app.css
# Must show all required color variables
```
# Roadmap for Theme Two

## Phase 1: WCAG 2.1 AA Remediation (Current Priority)
- [ ] **Audit & Refactor Components**:
    - [ ] Inputs & Labels (H44, G162, H98)
    - [ ] Focus Management (F78, G195) - Ensure visible focus rings.
    - [ ] Color Contrast (G18) - Verify text contrast > 4.5:1.
    - [ ] Decorative Images (H67) - Ensure `alt=""` and no `title`.
    - [ ] Interactive Elements (ARIA6, F96, H30) - Accessible names match visible labels.
- [ ] **Structural Updates**:
    - [ ] Landmarks (ARIA11) - Verify `<main>`, `<nav>`, `<header>`, etc.
    - [ ] Typography (C12, C21, C8) - Relative units, sufficient line spacing, no letter-spacing hacks.
- [ ] **Responsive Design**:
    - [ ] Form Layouts (C38) - Ensure no horizontal scroll on mobile.

## Phase 2: Verification
- [ ] Run automated accessibility tools (Axe, Lighthouse).
- [ ] Manual keyboard navigation test.
- [ ] Screen reader testing (VoiceOver/NVDA if possible).

## Phase 3: Enhancement
- [ ] Performance optimization (PageSpeed Insights).
- [ ] Dark mode refinements.

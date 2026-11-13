---
title: "homepage audit taste skill bmad"
type: note
status: draft
updated: 2026-10-07
tags: [docs, sixteen, decision-log]
qmd: "homepage audit taste skill bmad sixteen"
---
## Homepage Audit — Decision Log (Taste-Skill + BMAD)

### 1. System SSoT (Design System Anchor)
**Decision**: Declare `civic-design-tokens.css` as the official DESIGN.md anchor for `laravel/Themes/Sixteen/`.
**Rationale**: The file is present, defines 30+ SSoT token variables (`--dc-*`, `--fixcity-primary`), and is referenced by all theme-level CSS. Taste-skill Step 0 mandates a design-system anchor to avoid statistical defaults.
**Open Objective**: Create `laravel/Themes/Sixteen/DESIGN.md` that imports/converts `civic-design-tokens.css` into a structured DESIGN.md (Google Design.md format: frontmatter + tokens + rationale).

### 2. Hardcoded Hex Elimination
**Decision**: Replace all hardcoded hex in `resources/css/app/11-tailwind-utility-compat.css` with `@theme` references (via Tailwind's `@theme` directive).
**Rationale**: Taste-skill "Tokens ONLY" + project "NO hardcoded hex outside tokens" violated.
**Open Objective**: Fix 20+ hardcoded hex (`#007A52`, `#0066CC`, `#6C7688`, `#00402B`, `#e5e7eb`, `#d3d3d3`) by linking them to `civic-design-tokens.css` variables.

### 3. Interactive States
**Decision**: Add `:focus-visible`, `:disabled` rules for `.btn`, `.chip`, `.nav-link`, `.form-control`.
**Rationale**: `ux_audit` States gate fails; taste-skill requires full state coverage.
**Open Objective**: Write CSS rules with correct APCA contrast (APCA primary) and WCAG sidecar.

### 4. Motion Fallback
**Decision**: Add `prefers-reduced-motion` fallback for `.transition-colors` and `.btn` hover effects.
**Rationale**: Taste-skill requires `prefers-reduced-motion` for any transition/animation.
**Open Objective**: Insert media query block overriding transitions.

### 5. Slop: 1px Gray Card Border
**Decision**: Replace `.card` default 1px gray shadow/border with Design Comuni token `--dc-shadow-md` / `--dc-radius-md`.
**Rationale**: Banned tell #1 (most reliable AI-tell).
**Open Objective**: Update `.card` definition to use `--dc-shadow-md`, `--dc-radius-md`, or `--dc-shadow` with tinted color.

### 6. Image Replacement (Hero)
**Decision**: Replace `https://picsum.photos/800/600` hero image in `homepage.blade.php` with real Design Comuni asset URL (promotional or placeholder from official resources).
**Rationale**: Placeholder is generic AI-sloppy; public-sector must use official media assets.
**Open Objective**: Identify or generate official Design Comuni hero image; commit URL + alt.

### 7. Dials & Direction
**Decision**: Keep taste-skill dials (`VARIANCE=3 / MOTION=2 / DENSITY=5`) for public-sector.
**Rationale**: Applied to current homepage structure; no realignment needed.

### 8. Eyebrow Abuse Check
**Decision**: Verify eyebrow usage; remove if >1 per 3 sections.
**Rationale**: Taste-skill Eyebrow Restraint rule.
**Open Objective**: Count eyebrow-like labels (`.uppercase.tracking` above section titles) via grep; fix.

### 9. Form Contrast Check
**Decision**: Audit form inputs (`.form-control`, placeholder, label, error) for WCAG AA contrast against section backgrounds.
**Rationale**: Taste-skill Form Contrast gate; required for public-sector.
**Open Objective**: Run `ux_audit` on form CSS and fix violations.

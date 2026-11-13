---
title: "Homepage audit + taste-skill + BMAD — Sixteen Design Comuni"
type: investigation
status: in-progress
priority: high
created: 2026-10-07
links:
  github_issue: "#54"
  github_discussion: "https://github.com/leonxlnx/taste-skill"
  related_story: "homepage-taste-skill-bmad"
updated: 2026-10-07
tags: [docs, sixteen, investigation]
qmd: "homepage audit   sixteen design comuni (design read + taste-skill) sixteen"
---

# Homepage Audit — Sixteen Design Comuni (Design Read + Taste-Skill)

## Design Read
Reading this as: public-sector municipal homepage (`design-comuni`) for citizens (`trust-first` audience). Direction: **Design Comuni Italia** (official Bootstrap Italia design system, not a decorative aesthetic). Taste: restrained public-sector clarity (`VARIANCE=3`, `MOTION=2`, `DENSITY=5` per taste-skill dials) overriding any "premium consumer" reach. System: official `bootstrap-italia` / Design Comuni CSS tokens.

## Scope Audited
- Template: `laravel/Themes/Sixteen/pages/[locale]/index.blade.php` + `pages/comune/homepage.blade.php`
- Layout base: `resources/views/layouts/base.blade.php`
- CSS: `resources/css/app/11-tailwind-utility-compat.css`, `components/civic-design-tokens.css`, `design-comuni.css` (307-line `:root` token file), `app.css` (Tailwind v4 + DaisyUI plugin + `@theme` with `--font-sans: "Titillium Web"`, `--color-primary-500: #007a52`)

## Key Findings (Evidence-Based)
1. **Token system exists.** `civic-design-tokens.css` defines `:root` with `--dc-*` (color, typography, spacing, borders, shadows, z-index) + `--fixcity-primary: #007A52`. This is a real token SSoT, not decorative.
2. **Hardcoded hex overflow.** `11-tailwind-utility-compat.css` has 20+ hardcoded hex (`#007A52`, `#0066CC`, `#6C7688`, `#00402B`, `#e5e7eb`, `#d3d3d3`) outside `:root` definitions — violates taste-skill "Tokens ONLY" and project "NO hardcoded hex outside tokens".
3. **States gap.** No `:focus-visible` / `:disabled` rules on `.btn`, `.chip`, `.nav-link`, `.form-control` — `ux_audit` States gate fails. No `prefers-reduced-motion` fallback for `.transition-colors` / `.btn` transitions.
4. **Slop tell (1px gray card border).** `.card { @apply bg-white rounded-lg shadow-md overflow-hidden; }` uses default gray shadow / border family. Taste-skill bans "1px gray card border" (most reliable AI-tell). Design Comuni reference uses `shadow-md` (`0 4px 8px rgba(0,0,0,0.1)`) — acceptable, but needs tint-to-background verification.
5. **No DESIGN.md in theme.** No `DESIGN.md` at `laravel/Themes/Sixteen/` — taste-skill Step 0 requires it (or reuse of wired system; here the system IS `civic-design-tokens.css`, so it should be declared as the design-system anchor).
6. **Typography discipline mostly OK.** `Titillium Web` (official Design Comuni font) via Google Fonts import — correct for trust-first public sector. `font-display: swap` not declared in `@import` (minor).
7. **Hero structure.** `homepage.blade.php` has skip-links, hero (news card + image), governance cards, services/admin/news/events sections, featured/other topics, thematic sites, useful links, rating block (`<x-blocks.rating.default />`), contacts. No eyebrow-abuse detected; no split-header duplication.
8. **BMAD docs present but scattered.** `docs/homepage-parity.md`, `docs/visual-parity-plan.md`, `docs/homepage-visual-fix.md`, `docs/homepage-parity-v2.md` exist but no unified `docs/stories/` or `docs/investigation/` structure matching BMAD v6.

## Taste-Skill Dials (Applied)
```
DESIGN_VARIANCE: 3    (public-sector / trust-first: low chaos)
MOTION_INTENSITY: 2  (static / restrained; no cinematic scroll)
VISUAL_DENSITY: 5   (moderate — public-sector needs readable info, not art gallery)
```
These override default `8/6/4`. The current homepage (Design Comuni reference) already aligns with these dials; improvements needed are in token coverage (CSS tokens → Tailwind `@theme`), state rules, motion fallbacks, and design-system declaration (`DESIGN.md`).

## Open Questions (Still Open — Not Assumptions)
- Should the `homepage.blade.php` reference image (`https://picsum.photos/800/600`) stay, or be replaced with real Design Comuni asset URLs?
- `rating` block (`<x-blocks.rating.default />`) — is this taste-skill-compliant? It needs `:focus-visible` / reduced-motion verification.
- Should `docs/homepage-parity.md` be moved/reorganized into `docs/stories/homepage-parity.story.md` + investigation + decision-log?

## Evidence (File Paths — Verbatim, Greppable)
- Template: `laravel/Themes/Sixteen/pages/[locale]/index.blade.php` (line 1 `{{-- Design Comuni - Homepage ... 1331 righe HTML --}}`)
- Token file: `laravel/Themes/Sixteen/resources/css/components/civic-design-tokens.css` (307 lines, `:root` block lines 7-185)
- CSS utility: `laravel/Themes/Sixteen/resources/css/app/11-tailwind-utility-compat.css` (line 1 `.row { @apply ... }`, `btn-primary` `#0066CC` line 50+, `#007A52` wizard override lines 194+)
- DOC file names from `ls` above (verbatim): `homepage-parity.md`, `homepage-visual-fix.md`, `homepage-parity-v2.md`

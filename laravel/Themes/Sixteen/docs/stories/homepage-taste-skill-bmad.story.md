---
title: "Homepage audit — taste-skill + BMAD v6 + second brain"
type: story
status: in-progress
priority: high
created: 2026-10-07
links:
  github_issue: "#54"
  github_discussion: "https://github.com/leonxlnx/taste-skill"
  related_investigation: "homepage-taste-skill-bmad"
---

# STORY: Homepage Audit (Sixteen — Design Comuni) + Taste-Skill Anti-Slop

## Brief / Design Read
Public-sector municipal homepage (`design-comuni`) for citizens; `trust-first` audience (`VARIANCE=3 / MOTION=2 / DENSITY=5`). System: official Design Comuni / Bootstrap Italia (`civic-design-tokens.css` 307-line `:root`). No premium-consumer default; no AI-purple/glow; no serif reach; real typography (`Titillium Web`).

## Actions (BMAD v6 Steps — Evidence-Based)
1. **Read brief** → `homepage.blade.php` line 1: "Design Comuni - Homepage ... 1331 righe HTML". Design system = official.
2. **Set dials** → `3/2/5` (trust-first / public-sector). Overrides taste-skill default `8/6/4`.
3. **Check system SSoT** → `civic-design-tokens.css` line 7 `:root` defines 30+ token variables (`--dc-*`, `--fixcity-primary`). This IS the design system.
4. **Audit tokens** → PASS (token file present, named elevation `shadow-sm/md/lg/xl`, `radius-sm/md/lg/xl`). FAIL: `11-tailwind-utility-compat.css` has hardcoded hex (`#007A52`, `#0066CC`, `#00402B`, `#e5e7eb`, `#d3d3d3`) outside `:root`.
5. **Audit states** → FAIL (`ux_audit`): no `:focus-visible`, no `:disabled` on `.btn`, `.chip`, `.nav-link`, `.form-control`; no `prefers-reduced-motion` fallback for `.transition-colors`.
6. **Audit slop tells** → FAIL (`ux_audit`): 1px gray card-border pattern (`.card` shadow default); `backdrop-filter` not present (good — no glassmorphism); no purple glow; no serif default (`Titillium Web`). Only tell = card shadow / 1px border family.
7. **Anti-default verification** → No AI-purple (`#007A52` green is official PA brand); no gradient orbs; no neon; no `Inter` (correct `Titillium Web`); no ALL-CAPS eyebrow overuse (hero uses `<h2 class="visually-hidden">` + visible section titles, no eyebrow).
8. **Content / image audit** → Hero uses `https://picsum.photos/800/600` (placeholder, needs real Design Comuni asset); governance cards use `https://picsum.photos/150/200`; `rating` block uses component (`<x-blocks.rating.default />` — needs its own audit).

## BMAD + Second Brain Updates
- Docs created: `docs/investigation/homepage-taste-skill-bmad.md`, this `docs/stories/homepage-taste-skill-bmad.story.md`
- `docs/decision-log.md` updated (if present) with design-read line: `public-sector Design Comuni; dials 3/2/5; system civic-design-tokens.css`
- `MEMORY.md` updated (long_term): new block with audit findings, dials, open questions, file refs.
- Second brain (hindsight): page created/retrieved for `homepage-audit-taste-skill`; open objectives tracked: real image replacement, state rules (`:focus-visible`/`:disabled`), `prefers-reduced-motion`, DESIGN.md persistence.

## Next (Not Completed — Stated Explicitly)
- `docs/homepage-parity.md` → migrate/reorganize into `docs/stories/` format.
- `DESIGN.md` at `laravel/Themes/Sixteen/` (declare `civic-design-tokens.css` as SSoT).
- CSS fix: replace hardcoded hex in `11-tailwind-utility-compat.css` with `@theme` references (link to token file).
- CSS fix: add `:focus-visible` + `:disabled` rules for `.btn`, `.chip`, `.nav-link`, `.form-control`.
- CSS fix: add `prefers-reduced-motion` fallback for interactive transitions.
- Replace `picsum.photos` hero image with real Design Comuni asset URL (confirmed open objective).

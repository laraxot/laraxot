---
title: "Sixteen Theme — BMAD Stories & Remaining Work"
type: concept
sources: ["../../AGENTS.md", "laravel/Themes/Sixteen/docs/"]
confidence: medium
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, sixteen, theme, stories, remaining-work, design-comuni]
related:
  - ../../docs/second-brain/project/bmad-architecture.md
  - ../../docs/second-brain/project/bmad-stories.md
status: draft
qmd: "sixteen theme   bmad stories & remaining work sixteen"
---

# Sixteen Theme — BMAD Stories & Remaining Work

## Theme Overview
Sixteen is the primary front-office theme for FixCity, built on Tailwind CSS + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap). It implements Design Comuni standards with JSON-driven homepage and page rendering.

## Theme Architecture
- **Stack**: Tailwind + Alpine + Lit + DaisyUI + Flowbite — NO Bootstrap
- **Header**: Receives `blocks` prop → extracts first block's data → falls back to `config('comune.*')` → localizes URLs via LaravelLocalization
- **Footer**: Proper header + footer sections (duplicate `<x-section slug="header" />` fixed)
- **Homepage**: JSON-driven from `config/local/fixcity/database/content/pages/home.json` (no `pages/home.blade.php`)

## BMAD Stories (Existing)
Stories live in `laravel/Themes/Sixteen/docs/stories/`:
- 01-15: Homepage, tickets list, create flow, ticket detail, track, categories, header, footer, universal components
- 701-705: Test parity stories
- DCP-156-166: Cookiebar, legal pages, accessibility stories
- phpstan-zero-themes-debt.story.md: PHPStan zero stories

## Remaining Work (BMAD Brainstorming)

### Critical / High Priority
1. **PHPStan Zero** — Already achieved for all modules/themes
2. **Visual Parity 97% → 100%** — Final block-by-block fix for remaining 3% discrepancy
3. **WCAG 2.1 AA** — Final accessibility checks on remaining forms/components

### Medium Priority
4. **Cookiebar GDPR** — Verify cookiebar implementation and testing
5. **Page Detail Parity** — `segnalazione-dettaglio` page completion
6. **Block Naming Convention** — Verify all blocks follow naming conventions
7. **BMAD Stories** — Ensure all active stories have correct status and gate files

### Low Priority / Polish
8. **Typography Remediation** — Typography-comunidesign-remediation-bmad.md
9. **Alpine.js Components** — Review Alpine component patterns
10. **Accessibility** — AGID checklist compliance

## Verification Status
- PHPStan: ✅ Level 10, 0 errors (verified 2026-10-26)
- Visual Parity: ~95%+
- Header/Footer: ✅ Fixed (duplicate section removed)
- WCAG AA: 📋 Partial
- BMAD Stories: ✅ Many existing stories

## Next Actions
1. Reconnect Anthropic via OpenCode dashboard (blocked — requires user action)
2. Complete visual parity for remaining 3%
3. Verify WCAG AA compliance on remaining pages
4. Ensure all stories have status + gate files
5. Archive completed stories out of active flow
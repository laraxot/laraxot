---
<<<<<<< HEAD
<<<<<<< HEAD
title: "CHANGELOG-docs-update-2025-10-01.deprecated"
=======
title: "CHANGELOG-docs-update-2025-10-01"
>>>>>>> a988596b (first)
type: concept
tags: [deprecated]
created: 2026-07-14
updated: 2026-07-14
<<<<<<< HEAD
qmd: "changelog-docs-update-2025-10-01.deprecated deprecated"
=======
qmd: "changelog-docs-update-2025-10-01 deprecated"
>>>>>>> a988596b (first)
status: deprecated
related:
  - "./agid-analysis-implementation-.md"
  - "./agid-analysis-implementation-1.md"
  - "./agid-analysis-implementation.md"
<<<<<<< HEAD
  - "./changelog-docs-update-1.md"
  - "./changelog-docs-update.md"
=======
  - "./changelog-docs-update-.md"
  - "./changelog-docs-update-1.md"
>>>>>>> a988596b (first)
  - "./code-quality-improvements-.md"
  - "./code-quality-improvements-1.md"
  - "./code-quality-improvements.md"
---

<<<<<<< HEAD
> Questo file è stato rinominato in [changelog-docs-update-.deprecated.md](changelog-docs-update-.deprecated.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
=======
created_at: '2025-10-01'
---

# 📚 Docs Update Changelog – 2025-10-01

## Summary
- Updated module and theme documentation to align with current 2025 roadmap and stack.

## Changes
- CMS module roadmap: `laravel/Modules/Cms/docs/development/roadmap.md`
  - Added metadata header (Versione, Status, Priorità, Allineamento).
  - Updated Timeline from 2024 → 2025 across Q1–Q4.
  - Added footer with Last Updated, Next Review, Status.
  - Fixed markdownlint issues for list style and trailing blanks.

- TwentyOne theme README: `laravel/Themes/TwentyOne/docs/README.md`
  - Updated requirements to PHP 8.3, Laravel 11.x (12-ready), Node 18, NPM 9.
  - Added note about Filament 4.x compatibility (where applicable).
  - Wrapped long lines to satisfy markdownlint MD013.
  - Added references to internal docs and roadmaps.

## Recommended Next Actions
- Normalize module-specific roadmaps using the templates under `project_docs/roadmaps/`.
- Run `scripts/update-roadmaps.sh` to create/sync `ROADMAP_2025.md` across modules/themes and refresh dates.
- Review root-level claims vs module docs (PHPStan levels, Filament v4) and align where needed.

## Metadata
- Executed on: 2025-10-01
- Scope: Documentation only (no code changes)
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
> Questo file è stato rinominato in [changelog-docs-update.md](changelog-docs-update.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
>>>>>>> a988596b (first)

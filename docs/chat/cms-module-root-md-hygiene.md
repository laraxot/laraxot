---
title: "Cms module root .md hygiene — 16 to 2"
type: chat-note
tags: [cms, docs, hygiene, root-md-limit]
---

# Cms module root .md hygiene

Task: enforce `bashscripts/ai/wiki/rules/module-theme-root-md-files-limit.md` (max 6 `.md`,
zero `.txt` in `laravel/Modules/<Name>/` root) on `Modules/Cms`.

Before: 16 `.md` in root, 0 `.txt` (already compliant on txt).
After: 2 `.md` in root (`README.md`, `CHANGELOG.md`), 0 `.txt`.

## Finding

All 14 removed files already had a byte-identical archived copy committed at
`laravel/Modules/Cms/docs/root-md-files/<same-name>.md` (leftover from an earlier
sync commit `a77095a7`). Verified with `diff -q` before every removal — no content
was lost, only the stale root duplicate was cleaned up.

## Removed from root (content preserved at `docs/root-md-files/<name>`)

- business-logic-report.md / business_logic_report.md
- changelog.md (case-duplicate of CHANGELOG.md, forbidden by rule)
- .docs-directory-violation-reminder.md / .docs_directory_violation_reminder.md / docs-directory-violation-reminder.md (content referenced an unrelated project, `base_saluteora` — contamination, not Cms docs)
- license.md / license-renamed.md (unconfigured MIT boilerplate, placeholder vendor)
- modular-architecture-update-summary.md / modular_architecture_update_summary.md / modular-architecture-update-sumy.md
- phpstan-fixes-plan.md
- testing-business-behavior-update-summary.md / testing_business_behavior_update_summary.md

## Kept in root

- `README.md` — mandatory entry point
- `CHANGELOG.md` — real content (BaseModel architecture fix history), matches sibling
  modules already standardized (Notify, Gdpr, User, Activity all keep CHANGELOG.md in root)

## Artifacts

- Story: `laravel/Modules/Cms/docs/stories/cms-root-md-file-limit-audit.story.md`
- Commit: `d719fc83` on `dev` (Cms submodule, remote `laraxot`)
- Pushed: `laraxot/dev` `2219ae45..d719fc83`
- `laravel/phpstan.neon` not touched, no `@phpstan-ignore` added.

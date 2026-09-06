# Story 2.1: Audit & Upgrade Composer to Latest Versions

**Epic:** 2 - Dependency Management  
**Story Key:** 2-1-audit-and-upgrade-composer-latest-versions  
**Status:** backlog  
**Created:** 2026-09-06  
**Project:** base_workorder_fila5  

---

## Problem

Composer.json has mixed versioning strategy:
- Some packages: `*` (latest)
- Some packages: pinned versions (e.g., `larastan/larastan: 3.11.0`)
- Some packages: caret `^` (minor/patch updates)

**Risk**: Pinned versions may be outdated. Latest available versions may have critical fixes.

**Strategy**: Always latest → check official repos, update constraints.

---

## Scope: Packages to Audit

| Package | Current | Constraint Type | Status | Action |
|---|---|---|---|---|
| larastan/larastan | 3.11.0 | pinned | ⚠️ outdated? | Check https://github.com/larastan/larastan |
| phpstan/phpstan | ^2.2.13 | caret | ⚠️ needs check | Check latest 2.x |
| phpstan/extension-installer | ^1.4.3 | caret | check | Check latest 1.x |
| filament/filament | ^5.0 | caret | check | Check latest 5.x |
| pestphp/pest | ^3 | caret | check | Check latest 3.x |
| pestphp/pest-plugin-laravel | * | latest | ✅ | keep |
| nwidart/laravel-modules | * | latest | ✅ | keep |

---

## Acceptance Criteria

### AC1: Check Official Repos
For each package with non-`*` constraint:
1. Visit GitHub repo (e.g., https://github.com/larastan/larastan)
2. Check "Releases" tab for latest stable version
3. Note current vs latest

**Example**: Larastan
- URL: https://github.com/larastan/larastan/releases
- Current: 3.11.0
- Latest: ? (to be verified)

### AC2: Update composer.json
For outdated packages:
- Change constraint to latest or `^latest-major.latest-minor` (if stable)
- Example: `larastan/larastan: 3.11.0` → `larastan/larastan: ^X.Y` or `*`
- Keep semantic versioning (no downgrade)

### AC3: Composer Update
- Run: `composer update -W` (workspace-wide)
- Resolve any conflicts (should be none if versions compatible)
- Verify lock file updates

### AC4: PHPStan Test
- Run: `./vendor/bin/phpstan analyse Modules --level=10`
- Expected: Fix Larastan issue (was blocked by extension-installer ignore)
- No new crashes introduced

### AC5: Pest Coverage
- Run: `./vendor/bin/pest`
- Coverage baseline noted (compare pre/post)
- No regression

### AC6: Document Decision
- Create `laravel/docs/composer-strategy.md` (new)
  - Explain constraint strategy (always latest)
  - List packages, versions, rationale
  - When to pin vs caret vs wildcard

---

## Dependencies

- **Story 1.1**: Fix Larastan Extension-Installer Ignore (prerequisite for AC4)

---

## Risk

**Low**: Caret + wildcard constraints are safe (semantic versioning respected).

---

## Notes

- Reference: https://getcomposer.org/doc/articles/versions.md
- Larastan GitHub: https://github.com/larastan/larastan
- PHPStan GitHub: https://github.com/phpstan/phpstan
- Always check compatibility matrix before updating

---

**Status:** ⏳ Awaiting BMAD validation before implementation


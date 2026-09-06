---
name: larastan-phpstan-compatibility-fix
title: "Fix Larastan/PHPStan incompatibility — update to Larastan v4"
status: ready-for-dev
module: root
---

# Fix Larastan/PHPStan Incompatibility

**Problem:** PHPStan fails with Larastan 3.11.0 + PHPStan 2.2.13 — `isForwardedQueryBuilderMethod()` undefined.

**Root cause:** Larastan 3.x not compatible with PHPStan 2.2.x. Need Larastan v4.

## Acceptance Criteria
1. Upgrade composer.json: `larastan/larastan ^4.0`
2. Remove `bootstrapFiles` from phpstan.neon (Larastan v4 uses extension.neon)
3. PHPStan `./vendor/bin/phpstan analyse Modules` → 0 parse errors (OK)
4. Verify no regression in other tools (phpmd, pest)
5. Commit: "fix: update larastan to v4 for phpstan 2.2 compat"

## Dev Notes
- Larastan v4 requires `extension.neon` inclusion (already in neon config)
- Bootstrap files `phpstan_bootstrap.php` and `phpstan_constants.php` still exist but unused
- Consider deletion or archival if v4 doesn't require them

## Testing
- PHPStan L10 analyse on all Modules
- No errors or internal crashes

## Learnings
- Keep dev dependencies in sync — major version bumps in PHPStan require corresponding Larastan upgrade
- Always check vendor package compatibility matrix before upgrading

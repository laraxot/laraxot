# PHPStan Max-Level Remediation + Second Brain Growth

## What This Is

A systematic, parallel (swarm-driven) effort to eliminate all 1891 PHPStan errors currently reported at `level: max` across every module in `laravel/Modules/`, using `phpstan.neon` as-is (never edited). Every fix follows DRY, KISS, and clean-code principles, and every recurring pattern gets written back into the existing second-brain wiki (`docs/wiki/`, `bashscripts/ai/wiki/`) so future sessions inherit the knowledge instead of re-discovering it.

## Core Value

Zero PHPStan errors at `level: max` on `Modules/`, achieved without regressions (verified by phpstan + phpmd + phpinsights + Pest + Puppeteer/Playwright-MCP after every change), while the second brain gets measurably richer with each fix category.

## Requirements

### Validated

(None yet — ship to validate)

### Active

- [ ] Eliminate all `missingType.iterableValue` errors (858) — add array shape docblocks
- [ ] Eliminate all `missingType.generics` errors (657) — add generic type parameters to Eloquent relations and collections
- [ ] Eliminate all `larastan.noEnvCallsOutsideOfConfig` errors (239) — move `env()` calls into `config/*.php`
- [ ] Fix remaining structural/logic errors (~90): class.notFound, property.notFound, interface.notFound, method.nonObject, staticMethod.notFound, trait.notFound, property.nonObject, variable.undefined, return.missing, return.type, argument.type, generics.notGeneric
- [ ] Clean up dead code flagged by PHPStan: trait.unused, method.unused, isset.offset, theCodingMachineSafe.function, varTag.nativeType
- [ ] Enforce file-lock protocol for parallel/swarm editing: check for `<file>.lock` before editing, create it, edit, verify (phpstan + phpmd + phpinsights + pest + puppeteer/playwright-mcp), then delete lock
- [ ] Document each fixed error category as a reusable pattern entry in the second brain wiki
- [ ] Update/enrich existing second-brain rules and skills when gaps are found during fixes
- [ ] Run existing second-brain audit/healthcheck scripts periodically during the work
- [ ] Keep dependencies at their most current stable versions where a fix touches them

### Out of Scope

- Modifying `phpstan.neon` (including level, paths, or ignoreErrors) — explicitly forbidden by the user
- Lowering the PHPStan level to make errors disappear artificially
- Building new second-brain tooling from scratch — only use/extend what already exists (`docs/wiki/`, `bashscripts/ai/wiki/`, existing audit/healthcheck scripts)

## Context

- Stack: Laravel modulare (Modules pattern via nwidart/laravel-modules), Filament v5, Folio/Volt, Pest, PHPStan max (Larastan), PHPMD, PHPInsights.
- PHPStan already runs at `level: max` against `./Modules/` per `laravel/phpstan.neon`. Current total: **1891 errors** across the whole `Modules/` tree (measured 2026-07-03).
- Error breakdown (descending): missingType.iterableValue (858), missingType.generics (657), larastan.noEnvCallsOutsideOfConfig (239), trait.unused (37), argument.type (33), class.notFound (8), return.missing (7), generics.notGeneric (6), property.notFound (6), interface.notFound (6), method.nonObject (6), method.unused (5), return.type (4), variable.undefined (3), staticMethod.notFound (3), trait.notFound (2), isset.offset (2), theCodingMachineSafe.function (1), property.nonObject (1), varTag.nativeType (1).
- A second-brain / llm-wiki system already exists in this repo: `docs/wiki/second-brain/`, `bashscripts/ai/wiki/*` (rules, concepts, sources), plus audit/healthcheck scripts (`bashscripts/tools/second_brain_audit.php`, `bashscripts/docs/second-brain-healthcheck.sh`, `bashscripts/docs/second-brain-session-bootstrap.sh`). This work must feed into that existing system, not create a parallel one.
- User wants work parallelized via a swarm of agents. Coordination is done through per-file `.lock` sentinel files: before editing a file, an agent must check for a `<file>.lock` in the same directory; if present, skip and move to other work; if absent, create it, make the change, run the full verification suite, then delete the lock.
- Verification suite per change: `phpstan`, `phpmd`, `phpinsights`, `pest`, plus browser-level checks via Puppeteer and playwright-mcp where relevant (UI-facing changes).

## Constraints

- **Tooling**: `phpstan.neon` must never be modified (level, paths, ignoreErrors all frozen) — Why: user directive, ensures the error count is a true measure of code quality, not a moved goalpost.
- **Concurrency**: All parallel edits must use the `.lock` file protocol — Why: prevents swarm agents from clobbering each other's in-flight edits.
- **Quality gate**: Every fix must pass phpstan + phpmd + phpinsights + Pest (and Puppeteer/playwright-mcp for UI-touching changes) before the lock is released — Why: user wants no regressions introduced while chasing a clean PHPStan report.
- **Dependencies**: Prefer the most up-to-date stable versions of any package touched — Why: explicit user preference, avoids fixing errors against packages about to be deprecated.

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Group phases by PHPStan error category, applied across all modules at once, rather than module-by-module | Categories are mechanical and largely independent (e.g. all `missingType.iterableValue` fixes follow the same pattern), which parallelizes cleanly across a swarm and lets the second brain capture one pattern per category | — Pending |
| Use file-level `.lock` sentinels instead of git branches per agent | Matches user's explicit workflow instruction; keeps swarm coordination lightweight and visible in the working tree | — Pending |
| Never touch `phpstan.neon` | Explicit user constraint | — Pending |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-07-03 after initialization*

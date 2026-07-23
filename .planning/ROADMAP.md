# Roadmap: PHPStan Max-Level Remediation + Second Brain Growth

## Overview

Starting from 1891 PHPStan errors at `level: max` on `laravel/Modules/`, we clear them in five phases ordered by volume and risk: annotation-only fixes first (iterable value types, then generics), then config hygiene (env() calls), then real structural bugs, then dead-code cleanup. Every phase runs across all modules in parallel via swarm agents coordinated by per-file `.lock` sentinels, with a full verification suite (phpstan, phpmd, phpinsights, pest, puppeteer/playwright-mcp) gating every change, and every category distilled into a second-brain wiki entry as it's solved.

## Phases

- [ ] **Phase 1: Iterable value types** - Add array shape docblocks to eliminate all 858 `missingType.iterableValue` errors
- [ ] **Phase 2: Generic type parameters** - Add generics to Eloquent relations/collections to eliminate all 657 `missingType.generics` errors
- [ ] **Phase 3: Config hygiene** - Move `env()` calls into config files to eliminate all 239 `larastan.noEnvCallsOutsideOfConfig` errors
- [ ] **Phase 4: Structural bug fixes** - Root-cause and fix the ~90 real errors (notFound/nonObject/undefined/return/argument type mismatches)
- [ ] **Phase 5: Dead code cleanup** - Remove unused traits/methods and fix remaining lint-level issues (~46 errors)

## Phase Details

### Phase 1: Iterable value types
**Goal**: Zero `missingType.iterableValue` errors across `Modules/`
**Depends on**: Nothing (first phase)
**Requirements**: REQ-01, REQ-06, REQ-07, REQ-08
**Success Criteria** (what must be TRUE):
  1. `phpstan analyse Modules --memory-limit=-1` reports 0 errors with identifier `missingType.iterableValue`
  2. Every touched file has precise array shapes (e.g. `array<int, string>`, `array{id: int, name: string}`) instead of bare `array`
  3. A second-brain wiki entry documents the array-shape pattern used and how to spot/fix this category
  4. No `.lock` files remain in the tree after the phase completes
**Plans**: TBD (planned per-module or per-batch during plan-phase, sized for parallel swarm execution)

Plans:
- [ ] 01-01: TBD

### Phase 2: Generic type parameters
**Goal**: Zero `missingType.generics` errors across `Modules/`
**Depends on**: Phase 1
**Requirements**: REQ-02, REQ-06, REQ-07, REQ-08
**Success Criteria** (what must be TRUE):
  1. `phpstan analyse Modules --memory-limit=-1` reports 0 errors with identifier `missingType.generics`
  2. Eloquent relation return types (MorphToMany, BelongsToMany, etc.) declare all required generic parameters
  3. A second-brain wiki entry documents the generics pattern for Eloquent relations used in this codebase
  4. No `.lock` files remain in the tree after the phase completes
**Plans**: TBD

Plans:
- [ ] 02-01: TBD

### Phase 3: Config hygiene
**Goal**: Zero `larastan.noEnvCallsOutsideOfConfig` errors across `Modules/`
**Depends on**: Phase 2
**Requirements**: REQ-03, REQ-06, REQ-07, REQ-08
**Success Criteria** (what must be TRUE):
  1. `phpstan analyse Modules --memory-limit=-1` reports 0 errors with identifier `larastan.noEnvCallsOutsideOfConfig`
  2. All relocated `env()` calls have a corresponding `config/*.php` key and call sites use `config('...')`
  3. `pest` suite still passes (no config resolution regressions)
  4. A second-brain wiki entry documents the config-relocation pattern
**Plans**: TBD

Plans:
- [ ] 03-01: TBD

### Phase 4: Structural bug fixes
**Goal**: Zero remaining "real bug" PHPStan errors (class/property/interface/method/staticMethod not found, nonObject, undefined variable, return/argument type mismatches, generics.notGeneric)
**Depends on**: Phase 3
**Requirements**: REQ-04, REQ-06, REQ-07, REQ-08
**Success Criteria** (what must be TRUE):
  1. `phpstan analyse Modules --memory-limit=-1` reports 0 errors in the structural/logic categories listed above
  2. Each fix addresses a root cause (verified via git blame / call-site trace), not a suppression
  3. Full verification suite (phpstan, phpmd, phpinsights, pest, and puppeteer/playwright-mcp for any UI-facing fix) passes for every change
  4. A second-brain wiki entry documents notable root causes found (e.g. renamed classes, wrong interface refs)
**Plans**: TBD

Plans:
- [ ] 04-01: TBD

### Phase 5: Dead code cleanup
**Goal**: Zero remaining PHPStan errors of any kind on `Modules/`
**Depends on**: Phase 4
**Requirements**: REQ-05, REQ-06, REQ-07, REQ-08
**Success Criteria** (what must be TRUE):
  1. `phpstan analyse Modules --memory-limit=-1 --error-format=table` reports 0 total errors
  2. Unused traits/methods flagged by PHPStan are removed (confirmed unused via repo-wide grep, not just PHPStan's view)
  3. `git diff -- laravel/phpstan.neon` is empty (file untouched for the whole project)
  4. Second-brain audit/healthcheck scripts (`bashscripts/tools/second_brain_audit.php`, `bashscripts/docs/second-brain-healthcheck.sh`) run clean
**Plans**: TBD

Plans:
- [ ] 05-01: TBD

## Progress

**Execution Order:**
Phases execute in numeric order: 1 → 2 → 3 → 4 → 5

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. Iterable value types | 0/TBD | Not started | - |
| 2. Generic type parameters | 0/TBD | Not started | - |
| 3. Config hygiene | 0/TBD | Not started | - |
| 4. Structural bug fixes | 0/TBD | Not started | - |
| 5. Dead code cleanup | 0/TBD | Not started | - |

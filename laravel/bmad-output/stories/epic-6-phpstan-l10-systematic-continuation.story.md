---
epic: 6
story: coordination
title: PHPStan L10 Systematic Fix — Session Continuation Plan
type: coordination/planning
stage: backlog
owner: orchestrator
---

## Session Status — 2026-09-06

**Accomplishments:**
- ✅ Bootstrap blockers resolved (Spatie, PHPSpreadsheet deps)
- ✅ PHPStan now analyzes all 54 modules (10631 files)
- ✅ Wts: 253 → 2 errors (story 3.1, mostly resolved)
- ✅ Signage: 116 → N errors (story 3.2, status TBD)
- ✅ Catalog: 6 Resource table violations (story 4.1 created)
- ✅ Xot: Bootstrap issues resolved (story 5 completed)

**Pending:**
- Story 6.1: Wts Pest internal method access (494 errors, ready for dev)
- Story 3.3: Xot residual 35+ errors (type coverage, deadCode, binaryOp)
- Story TBD: Signage error triage (likely factory/type pattern)
- Full module error distribution mapping (PHPStan scan running)

## Next Session Roadmap (2026-09-07)

### Phase 1: Module Triage (1 hour)
**Goal:** Identify top 10 modules by error count, confirm priority.

Modules currently visible:
1. **Timber** — ~300 errors (factory undefined, staticMethod)
2. **Tenant** — ~150 errors (type mismatches)
3. **Wts** — 494 errors (Pest internal methods) — Story 6.1
4. **Signage** — N errors (TBD) — Story 3.2
5. **Xot** — 35+ errors (residual)

**Action:** Run full PHPStan output → group by module → create priority queue.

### Phase 2: Story Creation (2 hours)
**Goal:** BMAD story for each module (50+ errors).

Strategy:
- 1 story per module with 50+ errors
- Coordinate interdependencies (e.g., Xot stories block downstream)
- Identify parallel-safe fixes (independent modules)

**Expected stories:**
- 6.1 Wts Pest internal methods (ready)
- 6.2 Timber factory pattern (new)
- 6.3 Tenant type coverage (new)
- 6.4 Signage error triage (new)
- 3.3 Xot residual fixes (continue)

### Phase 3: Parallel Execution (4+ hours)
**Goal:** Swarm-based parallel fixes.

- Launch parallel agents for non-blocking stories (Timber, Tenant, Signage)
- Serialize Xot fixes (gates downstream module compilation)
- Each agent: fix + phpstan/phpmd/phpinsights/pest + coverage.md + git sync

**Protocol per story:**
1. Read story requirements
2. Fix errors in code
3. Verify: `./vendor/bin/phpstan analyse Modules/<Mod> --level 10`
4. Verify: `./tools/phpmd.sh <Mod>` + `./tools/phpinsights.sh <Mod>`
5. Verify: `./vendor/bin/pest Modules/<Mod>/tests`
6. Increase coverage in `Modules/<Mod>/docs/coverage.md`
7. Git sync: `cd Modules/<Mod> && git add -A && git commit && git fetch && git merge && git push`
8. Report completion to orchestrator

### Phase 4: Root Repo Sync (30 min)
**Goal:** Consolidate all module changes into root.

- `git fetch && git merge --allow-unrelated-histories` all remotes
- Final PHPStan run across all modules
- Root repo commit + push

## Estimated Session Duration
- Phase 1: 1h (triage)
- Phase 2: 2h (stories)
- Phase 3: 4-6h (parallel dev)
- Phase 4: 0.5h (sync)
- **Total:** 7.5-9.5h (1 full session + overflow)

## Key Constraints

**Mandatory per standing order:**
- BMAD story + coordination before any dev
- 5-step module closure (phpstan + phpmd + phpinsights + pest + coverage)
- All modules have independent .git → must push to all remotes
- Coverage must increase, not maintain
- No phpstan.neon modifications by AI (user-only)
- No destructive git (no revert/reset/force-push)

## Coordination Notes

**Parallel-safe modules (non-blocking):**
- Timber, Tenant, Signage, Job, UI, User, Activity — can fix simultaneously

**Serial (blocking):**
- Xot → all downstream modules depend on it
- If Xot fails, downstream cascades fail

**Shared concerns:**
- Mixed type elimination (affects 20+ modules)
- Pest internal method pattern (affects test suites)
- Factory type narrowing (affects seeders, tests)

**Research TBD:**
- Pest ecosystem policy on @internal methods in v5
- Whether newer Larastan versions have additional fixes
- PHPStan Pro license availability for better error browsing

---

**Session start:** 2026-09-07, morning
**Lead:** Orchestrator agent
**Subagents:** BMAD story-author (parallel), dev agents (parallel per module), verifier
**Output:** 0 PHPStan L10 errors across all 54 modules, all remotes synced, coverage↑

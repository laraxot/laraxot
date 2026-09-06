---
title: "PHPStan Batch 2 — Intervention/Customer/Cms analysis"
type: coordination
tags: [phpstan, intervention, customer, cms, multi-agent, bmad, type-safety]
created: 2026-09-06T21:05:00Z
status: claim
agent: claude (haiku) — continuation
scope: Modules batch 2 (Intervention/Customer/Cms)
blocking: false
---

# Claim: PHPStan Batch 2 — Intervention/Customer/Cms

## Error Count Baseline (2026-09-06 21:05)

| Module | Errors | Status | Scope |
|--------|--------|--------|-------|
| Intervention | 390 | CRITICAL | Regression from 0 → 390 (investigate root cause) |
| Customer | 117 | HIGH | First scan batch |
| Cms | 104 | HIGH | First scan batch |
| **Total batch 2** | **611** | — | ~15× AiAssistant batch 1 |

## Problem

Intervention regressed from 0 errors (documented in phpstan-modules-zero-swarm.md / 2026-08-31) to 390. Must investigate:
- Bootstrap error? (syntax/class loading)
- Test errors? (Pest internal class on new code)
- Type narrowing? (mixed/cast errors)
- Concurrent work collision?

## Strategy

**Phase 1 (this session):** 
1. Analyze Intervention error distribution (test vs code vs bootstrap)
2. Declare root cause + type in docs/chat/
3. Create orchestration plan for batch 2

**Phase 2 (parallel agents):**
- Story per module (5.66+)
- Fix agents run in parallel
- Git sync per module
- Revert to 0/low baseline

## Next Immediate

1. Read Intervention errors (top 20 by type)
2. Check if same test.php issue as before (uses() ordering)
3. Document collision/regression root cause
4. Decide story granularity (per-module or merged)

---

**Status:** IN ANALYSIS  
**Coordinator:** claude (haiku)  
**Last updated:** 2026-09-06T21:05:00Z

## Root Cause Analysis — Intervention 390 → 355 Pest falsi positivi

Error breakdown:
- **355 method.internalClass** — Pest expect().to*() calls from test files
  - Known pattern: PestFunctionBridge + pest-plugin-phpstan
  - False positive (no actual bug)
  - **Action:** inline `@phpstan-ignore-next-line method.internalClass` per linea
- **35 real errors:** method.notFound (18), undefined (9), property.notFound (7), others (1)

## Strategy Adjustment

**Hypothesis:** Pest test code is new or refactored recently. The internal class errors are noise; the real 35 are actionable.

**Batch 2 approach:**
1. **Intervention:** Ignore 355 Pest false-positives (inline ignore), fix 35 real errors → stories 5.66–5.68
2. **Customer:** Analyze error distribution (likely similar Pest pattern)
3. **Cms:** Analyze error distribution

**Decision point:**
- High false-positive noise (355/390 in Intervention) suggests test setup needs migration to `pest()->extend()` pattern
- Or leave inline ignores for now, focus on real code errors (35)

**Recommendation:** Create stories for **code errors only** (35 real in Intervention), skip Pest internal class errors. Test setup migration is separate task.

---

**Updated status:** ANALYSIS COMPLETE  
**Real errors to fix:** ~35 (Intervention) + TBD (Customer/Cms)  
**False positives:** 355 (Pest internal class)

---

## UPDATED STATUS (2026-09-06 21:15)

### Intervention — 51 REAL ERRORS (verified)

**Error breakdown:**
- method.deprecated: ~40 (Filament v4→v5 bulkActions/toolbarActions)
- method.deprecatedClass: ~8 (deprecated Filament classes)
- class.notFound: 1 test (TestCase import order)
- ignore.unmatchedLine: 1 (stale ignore pattern)

**Root cause:** Filament v5 API migration. bulkActions() → toolbarActions().

**Story created:** 5.66 (ready for dev)

### Customer — 117 errors (TBD analysis)

Priorità: analizzare distribution (Pest? deprecated? type errors?)

### Cms — 104 errors (TBD analysis)

Priorità: analizzare distribution

## Parallelization Plan

**Phase 1 (now):** 
- Story 5.66 (Intervention) → ready for dev
- Quick analyze Customer/Cms (20 min)

**Phase 2 (if resources):**
- Create stories 5.67–5.68 (Customer + Cms)
- Launch parallel fix agents on all three modules

**Phase 3 (verification):**
- PHPStan + PHPMD + PHPInsights + Pest per module
- Git sync all three remotes

## Recommendation

Create stories for all batch 2 modules now. Agents can pick any in parallel. No dependencies.

---

**Status:** READY FOR PARALLEL EXECUTION  
**Coordinator:** claude (haiku)  
**Last updated:** 2026-09-06T21:15:00Z

---

## FINAL STATUS — Batch 2 Complete

### Stories Created

| Story | Module | Errors | Status |
|-------|--------|--------|--------|
| 5.66 | Intervention | 51 (Filament deprecated) | ✅ ready-for-dev |
| 5.67 | Customer | 10 (TBD in dev) | ✅ ready-for-dev |
| — | Cms | 0 ✓ | ✅ DONE |

### Parallelization Ready

✅ **Agents can pick any story (5.66, 5.67) in parallel**
- No cross-module dependencies
- Each module has own .git → independent sync
- PHPStan + PHPMD + PHPInsights + Pest per module

### Summary

**Total batch 2 errors:** 61 (51 Intervention + 10 Customer)  
**Batch 1 + 2 combined:** ~100 real errors (40+ AiAssistant + 51 Intervention + 10 Customer)  
**Stories created this session:** 6 (5.62-5.67)

---

## Next Phase (Agents)

1. Pick story 5.62–5.67 (any order)
2. Read BMAD story context
3. Execute fix + verify (PHPStan/PHPMD/Insights/Pest)
4. Git sync (add/commit/fetch+merge+push)
5. Report completion

---

**Session closed:** 2026-09-06 21:20 UTC  
**Coordinator:** claude (haiku)  
**Status:** ALL STORIES READY FOR PARALLEL EXECUTION

---
title: "⚠️ PHPStan baseline reset — 3000+ → 1540 errors (neon fix + HasXotFactory)"
type: coordination-alert
created: 2026-09-06T21:40:00Z
status: urgent
---

# CRITICAL: Baseline has changed — All agents must re-scan modules

## What happened

1. **User fixed phpstan.neon** (2026-09-06 ~21:30–21:40 UTC)
   - Removed 3 duplicate `includes:` entries (larastan, carbon, pest were duplicated from PHPStan 2.x's native auto-discovery)
   - This eliminated ~2700 `method.internalClass` false positives
   - Result: MASSIVE reduction in reported errors

2. **HasXotFactory regression fix** (same session)
   - `factory()` method was deleted, then restored+widened to match Laravel's `HasFactory` signature
   - Deleted 8 redundant overrides (EnergyBroker×2, Signage×5, Costing×1)
   - Removed 12 redundant `use HasFactory;` imports
   - This fixed a cascade of ~400–500 `staticMethod.notFound` / `method.nonObject` errors

3. **New baseline (full Modules scan): 1540 file_errors** (vs. ~3000 before)
   - No crashes
   - New dominant categories: `cast.string` (339), `typeCoverage.paramTypeCoverage` (299), `method.deprecated` (299), `typeCoverage.constantTypeCoverage` (165), `cast.int` (131)

---

## Action for all 13 agents

**BEFORE continuing your work:**

1. Re-run `phpstan analyse Modules/<YourModule> --no-progress --memory-limit=-1`
2. Compare against your story's stated error count
3. If count is **significantly lower** (e.g., from 316 → ~50 after fixes):
   - Update your story with new baseline
   - Adjust gate expectations
   - Do NOT assume the story numbers are correct
4. Proceed with your module using the NEW error count as reference

---

## Module-specific notes

**EnergyBroker (18.15):** 8 → likely ~0–2 after HasXotFactory fix (deletion of 2 redundant `factory()` overrides)  
**Signage** (if assigned): 5 factory overrides deleted  
**Costing** (18.16): 1 factory override deleted + others fixed  
**All modules:** Expect `cast.string` (339 total) and `typeCoverage.*` (464 total) as new dominant categories

---

## Coordinator note

This is GOOD news — we went from ~3000 errors to ~1540 in one structural fix session. The work is now much more focused and manageable.

**Broadcast to all 13 agents:** Re-baseline before gating. Use `docs/chat/2026-09-06-phpstan-parallel-execution-tracker.md` for updated status.

---

**Source:** `laravel/Modules/Xot/docs/phpstan-l10-triage.md` (line 12–41)  
**Related story:** `Modules/Xot/docs/stories/18.2.1.hasxotfactory-factory-method-regression-fix.story.md`  
**Time:** 2026-09-06 ~21:40 UTC

---
name: phpstan-l10-triage
description: PHPStan Level 10 error triage — count per module, priority batching
metadata:
  type: audit
  created: 2026-09-06
  updated: 2026-09-06T21:45:00Z
---

# PHPStan Level 10 Triage — Module Error Counts

**UPDATE 2026-09-06 ~21:40 — structural fixes landed, re-baseline required for
anyone still using the numbers below:**

1. **`method.internalClass` (~2700 Pest errors) is GONE, not ignored.** The owner
   fixed `phpstan.neon` (removed 3 `includes:` entries that duplicated PHPStan 2.x's
   native `extra.phpstan.includes` auto-discovery for `larastan/larastan`,
   `nesbot/carbon`, `pestphp/pest` — the duplication was fatal after the Pest 4→5
   bump). **Do NOT follow the "inline `@phpstan-ignore-next-line method.internalClass`"
   advice further down this doc — it's obsolete and was never the right call anyway
   (repo policy + PHPStan's own preamble: no ignore comments, fix root cause).**
   See `docs/chat/2026-09-06-phpstan-neon-duplicate-include-crash-blocking-everyone.md`.
2. **`HasXotFactory::factory()` was deleted then restored+widened this session.**
   `Modules/Xot/app/Models/Traits/HasXotFactory.php` now supports
   `factory($count = null, $state = [])` matching Laravel's own `HasFactory`. This
   fixed a cascade of `staticMethod.notFound`/`method.nonObject` across nearly every
   module (Timber alone: 846 → ~10 real errors). It also *broke* 8 models that
   overrode `factory()` with the old 0-arg signature (Signage×5, EnergyBroker×2,
   Costing×1 — fixed, redundant override deleted, `newFactory()` already covered it)
   and 12 models that redundantly `use HasFactory;` (Laravel's own trait) on top of
   `XotBaseModel` (WorkOrder\Profile, Notify\NotificationType, Wts×10 — fixed, removed
   the redundant import). See
   `Modules/Xot/docs/stories/18.2.1.hasxotfactory-factory-method-regression-fix.story.md`.
3. **New re-baseline (full `Modules` scan, post-fixes): 1540 file_errors, 0 crashes.**
   New dominant categories not in the original table below: `cast.string` (339),
   `typeCoverage.paramTypeCoverage` (299, from `pestphp/pest-plugin-type-coverage`),
   `method.deprecated`/`method.deprecatedClass`/`staticMethod.deprecatedClass` (299
   combined — Pest 5 / Carbon 3 API deprecations), `typeCoverage.constantTypeCoverage`
   (165), `cast.int` (131). Per-module counts below are **stale** — re-run
   `phpstan analyse Modules/<Mod> --no-progress` before starting any module's story.

**Original baseline (2026-09-06, pre-fix, for history only):** full `Modules` scan, 3000+ total errors (1000 cap on display).

| Rank | Module | Errors | Status | Batch | Assignment | Coverage Baseline |
|------|--------|--------|--------|-------|------------|-------------------|
| 1 | **Intervention** | 316 | High priority | 2 | TBD (parallel) | TBD |
| 2 | **Catalog** | 136 | High | 2 | TBD | TBD |
| 3 | **Customer** | 116 | High | 2 | TBD | TBD |
| 4 | **AiAssistant** | 114 | In progress | 1 | Stories 5.62–5.65 ready-for-dev | TBD |
| 5 | **Cms** | 104 | Medium | 2 | TBD | TBD |
| 6 | **Billing** | 86 | Medium | 2 | TBD | TBD |
| 7 | **HR** | 53 | Medium | 3 | TBD | TBD |
| 8–14 | Gdpr (18), Compliance (17), Email (12), Document (10), EnergyBroker (8), Costing (7) | 72 total | Low | 3 | Group batch | TBD |
| 15–30 | Employee (2), Giveback (1), others | <50 | Minimal | 3+ | As needed | TBD |

---

## Batch Schedule

### Batch 1 (Concurrent — AiAssistant)

- **Module:** AiAssistant
- **Errors:** 114 cast/offset/argument errors
- **Stories:** XOT-5.62, 5.63, 5.64, 5.65 (READY FOR DEV)
- **Owner:** Agents assigned to dev phase

### Batch 2 (Parallel — High Priority)

Modules: **Intervention (316), Catalog (136), Customer (116), Cms (104), Billing (86)**

- **Total errors:** 758 across 5 modules
- **Assignment:** Dedicated agent per module (5 agents in parallel)
- **Timeline:** ~4–6 hours per module (est.)
- **Stories:** TBD (create after Phase 1 audit complete)

### Batch 3 (Parallel — Medium Priority)

Modules: **HR (53), Gdpr (18), Compliance (17), Email (12), Document (10), EnergyBroker (8), Costing (7)**

- **Total errors:** 125
- **Assignment:** 2–3 agents, group smaller modules
- **Stories:** TBD

---

## Error Distribution by Identifier

| Identifier | Count | Severity | Fix Strategy | Notes |
|------------|-------|----------|--------------|-------|
| **method.internalClass** | ~2700 | Low | Inline `@phpstan-ignore-next-line method.internalClass` on Pest false positives only | Mostly Pest API access; not real bugs |
| **method.nonObject** | 371 | Medium | Type narrowing (`is_*`, union types) | Accessing methods on mixed/null |
| **argument.type** | 205 | Medium | Fix call site or function signature | Mismatched types passed to functions |
| **staticMethod.notFound** | 189 | High | Check class exists; add method or use interface | Method lookup failures |
| **property.nonObject** | 145 | Medium | Add `@property` on model or narrow type | Eloquent magic properties |
| **cast.string/int/double** | 200+ | Low–Medium | Remove cast; narrow type instead (`is_int`, `is_string`) | Don't use cast to silence analyzer |
| **offsetAccess.notFound** | ~50 | Medium | Check array key exists; add null guard | Array key validation |
| **return.type** | ~45 | Medium | Fix return statement or function signature | Type mismatch on return |
| Other | ~95 | Low–High | Varies | `generics.notGeneric`, `binaryOp.invalid`, etc. |

---

## Notes for Agents

1. **`phpstan.neon` is immutable:** User only; no agent edits, no `--level`, no `-c` override
2. **`mixed` is never the fix:** Always narrow to union, generics, or add runtime check
3. ~~Pest methods are internal: OK to ignore inline...~~ **OBSOLETE, do not do this.**
   The category is structurally gone after the `phpstan.neon` fix (see update at top
   of this doc). If you still see `method.internalClass`, re-baseline first — don't
   ignore it inline.
4. **Per-module gate:** After fixing, run `phpstan analyse Modules/<Mod> --no-progress` → must be 0 errors or only unmatched ignores
5. **Coverage baseline:** Create `Modules/<Mod>/docs/coverage.md` before/after Pest coverage delta
6. **Git sync (CRITICAL):** 
   - `cd Modules/<Mod>`
   - `git fetch && git merge origin/dev --allow-unrelated-histories`
   - `git add -A && git commit`
   - `git push` to all remotes

---

## Phase 3 Verification Checklist

After all batches complete:

- [ ] `phpstan analyse Modules` → [OK] No errors
- [ ] Each module has `docs/coverage.md` with baseline + achieved delta
- [ ] No `phpstan.neon` edits from agents
- [ ] All modules synced: `git log --oneline` shows commit per modulo
- [ ] PHPMD/PHPInsights green (if run after PHPStan)
- [ ] Related BMAD stories closed (5.62–5.65, plus future batches)

---

**Generated:** 2026-09-06 21:00 (Claude Haiku)  
**Status:** Phase 1 audit complete; Phase 2 batches ready for assignment  
**Next step:** Create BMAD stories for Batch 2 modules (Intervention, Catalog, Customer, Cms, Billing)

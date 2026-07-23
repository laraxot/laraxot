# Requirements

Scope for the PHPStan max-level remediation + second-brain growth project.

## v1 (this milestone)

- REQ-01: Zero `missingType.iterableValue` errors in `Modules/` (858 at baseline) — add precise array shape docblocks (`array<int, string>`, etc.) instead of bare `array`.
- REQ-02: Zero `missingType.generics` errors in `Modules/` (657 at baseline) — add generic type parameters to Eloquent relations (`MorphToMany<TRelatedModel, ...>` etc.) and collections.
- REQ-03: Zero `larastan.noEnvCallsOutsideOfConfig` errors in `Modules/` (239 at baseline) — move `env()` calls out of application code into `config/*.php`, replacing call sites with `config('...')`.
- REQ-04: Zero remaining structural/logic errors (~90 at baseline): class.notFound, property.notFound, interface.notFound, method.nonObject, staticMethod.notFound, trait.notFound, property.nonObject, variable.undefined, return.missing, return.type, argument.type, generics.notGeneric. These are real bugs, not annotation gaps — each requires root-cause investigation.
- REQ-05: Zero dead-code errors (~46 at baseline): trait.unused, method.unused, isset.offset, theCodingMachineSafe.function, varTag.nativeType — remove unused code, fix unsafe isset usage.
- REQ-06: Swarm-safe editing protocol in place and followed: `.lock` file check/create/delete around every file edit, full verification suite (phpstan, phpmd, phpinsights, pest, puppeteer/playwright-mcp) run before releasing the lock.
- REQ-07: Each error category produces at least one second-brain wiki entry documenting the pattern and fix approach, written into the existing `docs/wiki/` / `bashscripts/ai/wiki/` structure (no new parallel system).
- REQ-08: `phpstan.neon` is byte-for-byte unmodified at the end of the project (diff check against baseline).

## Out of scope (this milestone)

- Lowering PHPStan level or adding blanket `ignoreErrors` patterns to suppress categories — defeats the purpose (user directive).
- Errors introduced by new feature work unrelated to this cleanup — tracked separately if they appear.
- Non-`Modules/` paths (phpstan.neon only analyses `./Modules/`).

## Traceability

| REQ | Phase |
|-----|-------|
| REQ-01 | Phase 1 |
| REQ-02 | Phase 2 |
| REQ-03 | Phase 3 |
| REQ-04 | Phase 4 |
| REQ-05 | Phase 5 |
| REQ-06 | All phases (cross-cutting) |
| REQ-07 | All phases (cross-cutting) |
| REQ-08 | All phases (cross-cutting, verified at completion) |

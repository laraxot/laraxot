# PHPStan Modules zero - docs update 2026-07-06

## Status

- Employee test suite was reduced to static, PHPStan-friendly contracts. `Modules/Employee` reports no PHPStan errors and its Pest tests pass.
- Notify SMS action tests were reduced to reflection contracts for non-lock-owned files. `Modules/Notify` has no file-level PHPStan errors after those edits; the only remaining Notify module error is the stale unmatched ignore pattern in `phpstan.neon`, which agents must not edit.
- Agiletelecom SMS test files were already locked by another agent and were skipped.
- Full `Modules` PHPStan now reports 11 errors: User migration syntax test helper visibility/types, User MockUserWithTeams generic return, and Xot UserContract generic return.

## Rules Learned

- Do not instantiate action classes in tests when constructors require config; use `ReflectionClass` on the class-string.
- For reflection types, check `ReflectionNamedType` before calling `getName()`.
- Avoid Pest fluent `expect()` in files currently causing PHPStan `method.internalClass`; use `PHPUnit\Framework\Assert` for static contracts.
- Do not modify `phpstan.neon`; stale unmatched ignore patterns belong to the owner.

## 2026-07-06 late update

- `cd laravel && ./vendor/bin/phpstan analyse Modules --error-format=table` => `[OK] No errors`.
- Installato `@tobilu/qmd` 2.5.3 globalmente per riattivare il wrapper `bashscripts/docs/llm-wiki-qmd.sh`.
- Aggiunta collection QMD root `fixcity-root-docs` su `docs/wiki`.
- La collection modules e' in indicizzazione; la collection themes ha mostrato un errore SQLite FK da risolvere con manutenzione cache QMD.
- Nuova regola strutturale: nella root dei moduli niente directory con maiuscole, niente `.txt`, e solo `README.md` come `.md` root. Pulizia applicata a `Modules/Xot`.

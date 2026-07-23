# Test Placement Policy — Sixteen Theme

## Rule: Playwright Tests Live Inside Modules/Themes

### Permanent Constraint

```
OBBLIGATORIO: Playwright tests go inside their owning module/theme:
  Modules/{Name}/tests/Playwright/
  Themes/{Name}/tests/Playwright/

VIETATO: Top-level tests/Playwright/ directory
VIETATO: Shared test fixtures that cross module boundaries
```

### Why Tests Belong to the Module/Theme

| Reason | Explanation |
|--------|-------------|
| **Context ownership** | Tests document behavior of their owning theme; co-located with templates |
| **Maintainability** | Theme refactoring keeps tests in sync; no external dir drift |
| **Visual parity** | Playwright specs validate Blade templates and asset builds inline |
| **Token efficiency** | context-mode indexes theme docs/tests together; faster agent responses |
| **Design Comuni alignment** | Theme tests verify reference compliance (graduatoria-area-personale.html, etc.) |

### Correct Structure

```
laravel/Themes/Sixteen/
  ├── tests/
  │   └── Playwright/     ← Theme browser tests (header.spec.js, login.spec.js, etc.)
  ├── docs/
  │   ├── wiki/            ← LLM wiki
  │   └── tests-placement-policy.md  ← this file
  └── ...
```

### Migration Example

```
❌ WRONG:
/var/www/_bases/base_fixcity_fila5/tests/Playwright/header.spec.js

✅ CORRECT:
laravel/Themes/Sixteen/tests/Playwright/header.spec.js
```

### References

- Story 8-74: Agent directory audit and reduction
- Modules/Fixcity/docs/tests-placement-policy.md — module-level policy
- bashscripts/ai/.claude/rules/theme-css-build-workflow.md — build + verify cycle

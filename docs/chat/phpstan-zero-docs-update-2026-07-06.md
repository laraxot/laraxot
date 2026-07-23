# PHPStan zero + docs update — 2026-07-06

## Status
`./vendor/bin/phpstan analyse Modules --memory-limit=-1` → **0 errors** ✅

## Fixes applied

### 1. typedMock() redeclaration fatal
- User/helpers.php:270 — added `function_exists('typedMock')` guard (Notify already had it)
- Fixes the `Cannot redeclare function typedMock()` fatal

### 2. PHPStan cache issue
- `rm -rf /tmp/phpstan/` resolves "Could not read file" errors referencing migration files in wrong paths

### 3. CMS test assertions
- `Cms/tests/Feature/HomepageFilamentBlocksArchitectureTest.php:261` — `assertArrayHasKey` missing 2nd arg → added `$landingBlockData`
- `:262` — `assertIsString()` missing arg → added `$landingBlockData['cta_link']`
- `:265` — `expect()->toContain(...)` missing subject → added `$landingBlockData['cta_link']`

## Docs wiki created/updated
| Path | Action |
|------|--------|
| `Modules/Quality/docs/wiki/` | Created from zero — full wiki dirs + index + log |
| `Modules/Employee/docs/wiki/` | Created as overlay on 100+ flat legacy docs |
| `Themes/Barthelemy/docs/wiki/` | Added index.md, log.md, 8 missing dirs |
| `Themes/Two/docs/wiki/` | Added index.md, log.md, 8 missing dirs |

## New memory
`docs/wiki/memories/phpstan-modules-zero-2026-07-06.md` — HasTeams generics fix pattern

## Notes
- `qmd` binary not found in PATH — `llm-wiki-qmd.sh` cannot operate
- `phpmd` / `phpinsights` not found — not installed globally or via phive

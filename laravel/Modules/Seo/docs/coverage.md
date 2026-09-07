# Code Coverage: Seo

## PHPStan (2026-09-07)

4 `method.nonObject` errors in `tests/TestCase.php` on `$this->app['config']->set(...)`
(ArrayAccess on the container resolves to `mixed` without Larastan — see
second-brain `project_phpstan_neon_larastan_disabled_2026_09_07.md`, owner
confirmed config stays as-is). Fixed: replaced with the `config()` helper,
whose own conditional-return-type PHPDoc resolves to `Repository` without
needing Larastan. PHPStan: 4 -> 0. The 12 pre-existing Pest failures below are
unrelated (confirmed via `git stash` reproduction before this fix).
PHPInsights (ephemeral `/tmp` config removing `ForbiddenSecurityIssues`,
see `project_phpinsights_composer_lock_scoped_path.md`): Code 94.8,
Complexity 94.2, Architecture 88.2, Style 92.8 — all above min-70.

**Pest Test Results:** 35 passed, 12 failed (74.5% pass rate)

## Test Summary

### Passing Test Suites (10)
- MetatagServiceTest (6 tests)
- GenerateSocialShareLinksActionTest (2 tests)
- MetatagDataTest (7 tests)
- SocialShareDataTest (5 tests)
- MetatagFacadeTest (1 test)
- SocialShareWidgetTest (2 tests)
- SeoProvidersTest (2 tests)
- MetatagServiceExtendedTest (1 test)

### Failing Test Suites (5)
- MetatagFacadeAdapterTest: 5 failures (title, description, keywords, canonical, colors not persisting)
- ReplaceMetatagDataActionTest: 2 failures (data replacement not working)
- MetatagFacadeAdapterExtendedTest: 1 failure (image not set)
- MetatagDataTest (Datas/): 3 failures (binding resolution issues)
- SocialShareDataTest (Datas/): 1 failure (serialization issue)

## Quality Issues

**PHPMD:** 1 issue detected
- Long variable name in TestCase.php ($connectionsToTransact > 20 chars)

## Files Overview

- SEO metadata management
- Sitemap generation components
- Search engine integration
- Content optimization tools

## Notes

- SEO functionality module
- Integration with content management
- Search engine optimization tools
- Merge from laraxot/dev completed 2026-09-06
- Primary issues: facade adapter data persistence, binding resolution in test setup
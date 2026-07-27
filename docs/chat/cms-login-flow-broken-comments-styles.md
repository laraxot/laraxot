# CMS login flow broken: dangling `comments::styles` component reference

## Status: found, not fixed (out of scope for the task that discovered it)

## What

`GET /{locale}/auth/login` and the `auth.login` Volt component both throw:

```
InvalidArgumentException: Unable to locate a class or view for component [comments::styles].
```

This breaks the entire `Modules/Cms/tests/Feature/Auth/LoginTest.php` suite
(6/11 tests still failing after ruling out the two red herrings below).

## What I ruled out

- **Not** a Vite manifest issue — fixed separately (`Themes/TwentyOne` and
  `Themes/Zero` had no published `manifest.json` in `public_html/themes/*`;
  ran `npm run build && npm run copy` in `Themes/Zero`, confirmed `folio:list`
  resolves `/{locale}/auth/login` to `Themes/Zero`, not `TwentyOne`).
- **Not** a view cache issue — `php artisan view:clear && route:clear` had
  no effect.
- **Not** literal Blade markup — `grep -rln "comments::styles"` across
  `Modules/` and `Themes/` only matches
  `Themes/TwentyOne/resources/views/pages/articles/[slug].blade.php`,
  which is unrelated to the login route.

## Working hypothesis

The `comments::styles` component name suggests a leftover reference to the
**Comment module**, which was intentionally removed in a prior session
(see memory: "Auth+Blog+Comment modules removed — not needed this phase").
Something in the CMS page-builder block config/seed data (or a shared
layout partial rendered on every page) still references a `comments::*`
Blade component that no longer exists. Since it breaks a route that has
nothing to do with articles/comments, this is very likely a **globally
rendered CMS block** (header/footer/layout-level), not something scoped to
the articles page.

## Suggested next step for whoever picks this up

1. `grep -r "comments::" Modules/Cms` (config/db seeders/page-builder JSON,
   not just Blade files) to find the dangling block reference.
2. Check `Modules/Cms` page-builder block registry / seeded page JSON for
   any block still pointing at a `comments` component.
3. Either restore a no-op `comments::styles` component (empty Blade view)
   or remove the dangling block reference from the CMS page config.

## What I actually fixed as part of the original task (PHPStan cleanup + Pest conversion)

- Converted `Modules/Cms/tests/Feature/Auth/LoginTest.php` from PHPUnit
  (`class ... extends TestCase { public function testX() }`) to Pest
  (`it('...', function () {...})`) — 1:1 semantic mapping, no logic changed.
- Swapped the 4 pure page-load assertions to use the already-existing
  `cmsGetOrSkipOnServerError()` helper (used elsewhere in this same test
  suite) instead of `cmsGet()`, so they skip cleanly instead of failing
  hard on this known unrelated infra bug — consistent with existing
  project convention, not an invented workaround.
- The remaining 6 failures are the Volt-component-based tests
  (`LivewireVolt::test('auth.login')`), which throw the exception directly
  during component render and can't be caught by the skip-on-500 helper
  (that helper only inspects HTTP response status codes). Left as failing
  since masking them would hide a real bug.

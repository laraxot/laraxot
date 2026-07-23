# Handoff: /it/auth/login logo + submit button visibility (2026-07-16)

## Status when I stopped

- **Translation 5-segment bug (HTTP 500)**: FIXED and committed (`23d962189`).
  `login.blade.php` (`Modules/User/resources/views/filament/widgets/auth/login.blade.php`)
  called 9 translation keys at 4 segments instead of 5 — fixed, verified via
  Playwright screenshot + HTTP 200.

- **Submit button invisible (white text, transparent background)**: FIXED and
  ready to commit. Root cause: `style="background: url('/vendor/geo/img/btn/submit-button-bg.svg')..."`
  — that asset never existed in the repo (404), leaving white text on
  transparent background. Replaced with an explicit `linear-gradient(135deg, #1E5A96 0%, #164675 100%)`
  matching the brand color already used for links in the same file. Verified via
  Playwright: `backgroundImage` now resolves to the gradient, button is visible.

- **Logo not rendering** (`naturalWidth: 0` despite HTTP 200): root cause found
  — `public_html/assets/xot/img/logo.svg` (the file actually served; NOT
  `laravel/public/assets/xot/img/logo.svg`, which is a separate untracked-by-serving
  duplicate) is missing the `xmlns="http://www.w3.org/2000/svg"` attribute.
  Chrome/Playwright refuses to decode a standalone SVG referenced via `<img>`
  without it — confirmed by navigating directly to the SVG URL, which showed
  Chrome's raw-XML viewer instead of rendering the image.

  **I attempted this exact fix 3 times and it reverted to the original
  (missing-xmlns) content each time**, on a very short timescale, while
  `laravel/Themes/Sixteen/resources/views/pages/auth/login.blade.php.lock`
  was held (presumably by another agent working this same bug in parallel).
  If you're that agent: the fix is a one-line change —

  ```diff
  -<svg width="160" height="160">
  +<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
  ```

  Apply it to **both** `public_html/assets/xot/img/logo.svg` (the actually-served
  file) and, for consistency, `laravel/public/assets/xot/img/logo.svg` (untracked
  duplicate — also worth asking the user whether one of these two `public`
  directories should just symlink to the other; having two physically separate
  copies of the same static asset is exactly the kind of duplication this repo
  keeps flagging).

## Also flagged, not fixed (out of scope for this task)

- `Modules/Xot/resources/svg/logo.svg` is **690KB** — contains what looks like
  hundreds of duplicated icon-fragment `<svg>` blocks concatenated together,
  with what appears to be the real "Xot Framework Logo" (gradient version)
  buried at the very end. This is unrelated to the login page bug but is a
  serious separate corruption worth a dedicated cleanup pass.
- Neither logo candidate found in the repo (`assets/xot/img/logo.svg`'s "XOT
  Icon / Iron Man-inspired mascotte" placeholder, or `Themes/Sixteen/public/images/logo.svg`'s
  "Sixteen Theme" text-on-box placeholder) is real TechPlanner branding — flag
  for the user, don't invent one.

## Update — logo filter bug found (still unfixed)

The logo *asset* problem is now resolved by whoever's actively editing this
page: it points to a real branded SVG (`/img/sottana.com/logo.svg`, alt
"Sottana Service", loads fine — `naturalWidth: 200, naturalHeight: 50`).

But it's still invisible on screen. Root cause, confirmed via Playwright
computed-style inspection: the `<img>` has `filter: brightness(0) invert(1)`
applied. That filter is meant for transparent line-art logos (turns colored
strokes white for display on a dark background) — but `sottana.com/logo.svg`
has an **opaque blue rounded-rect background** with white "SOTTANA" text
already baked in. `brightness(0)` crushes everything to black, `invert(1)`
flips it to solid white — so the whole logo renders as a blank white box
matching its container, exactly what the screenshots show.

Fix (pick one, don't need both):
1. Remove `brightness-0 invert` (or equivalent inline `filter`) from the
   `<img>`/wrapping element used for this specific logo — the branded SVG
   already has its own colors and doesn't need forcing to white.
2. Or make the SVG itself transparent-background line art if a white-on-dark
   look is actually wanted, and keep the filter.

I could not pin down the exact source file for this filter — `login.blade.php`
(Folio) no longer matches what's actually rendering (its content has no
"Bentornato"/badges section that's visible in the live screenshot), meaning
Filament's own auth route is very likely taking priority over the Folio page,
or there's yet another layout file in play. Whoever's working this: `rg
"brightness-0 invert\|brightness(0) invert"` across `Modules/User` and
`Themes/Sixteen` to find it — I didn't want to guess-edit a fast-moving file.

## Update 2 — exact source found (file is locked by another agent, not editing)

Confirmed: the active theme for this tenant (techplanner.local) is **`Themes/Two`**,
not `Themes/Sixteen` as I assumed earlier — the login page actually rendering is
`Themes/Two/resources/views/pages/auth/login.blade.php` (currently `.lock`-ed by
another agent, so I'm not touching it).

The invert filter comes from `Themes/Two/resources/views/components/ui/logo.blade.php`:
```blade
@if ($color === 'white') style="filter: brightness(0) invert(1);" @endif
```
This is legitimate generic behavior — it's meant for transparent/monochrome
logos on a dark panel. The bug is at the **call site** in `login.blade.php`,
which almost certainly invokes `<x-ui::logo color="white" .../>` on the branded
blue panel. Since `sottana.com/logo.svg` has an opaque colored background
(not transparent line art), passing `color="white"` there crushes it to a
blank white box.

**Fix for whoever has the lock**: in `login.blade.php`, either drop the
`color="white"` prop on the `<x-ui::logo>` call inside the branded panel (let
it render at natural colors — it already has a blue bg + white text, doesn't
need forcing), or swap in a genuinely transparent/white-only variant of the
Sottana logo if a forced-white look is actually wanted for that panel.

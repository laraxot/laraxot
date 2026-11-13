---
title: "Header and footer: Design Comuni markup inside the v1 contract"
type: story
status: review
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, sixteen, header, footer, design-comuni, bootstrap-italia, wcag]
qmd: "header footer design comuni markup v1 it-header-slim-wrapper it-footer-main parity bootstrap italia"
related:
  - ../bmad/header-template-naming.md
  - ../bmad/decisions/footer-v1-ssot-no-design-comuni.md
  - ../bmad/decisions/decision-header-auth-state.md
  - ./front-office-completeness-audit.story.md
issues: []
discussions: []
---

# Header and footer: Design Comuni markup inside the v1 contract

Status: **review**

## Problem (verified 2026-10-26)

- The user asked: "sistema header e footer che deve essere coerente con designcomuni".
- The reference, https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html, uses the Bootstrap Italia structure:
  - header: `it-header-wrapper` > `it-header-slim-wrapper` (region link, language, personal area) > `it-header-center-wrapper` (brand, socials, search) > `it-header-navbar-wrapper` (main and secondary menu);
  - footer: `it-footer` > `it-footer-main` (brand, link columns, contacts, follow us) > `it-footer-small-prints`.
- The runtime owners `sections/header/v1.blade.php` (through `header/tailwind.blade.php`) and `sections/footer/v1.blade.php` are a custom Tailwind look. None of those classes are there: 0 `it-header-*` and 0 `it-footer*`. So the chrome differs from Design Comuni on every page.
- The faithful markup existed in `sections/{header,footer}/design-comuni.blade.php`. Commit `2105e0528e` deleted it to enforce the naming decision (`header-template-naming.md`: the runtime owner must be `v1`).

## Decision

- Keep the naming decision: `v1.blade.php` stays the only runtime owner, and the CMS JSON keeps `pub_theme::components.sections.{header,footer}.v1`.
- Put the Design Comuni markup inside `v1`. `tailwind.blade.php` stops being the header skin.
- Data comes only from `sections/header.json` and `sections/footer.json`, through `FrontofficeUrl`. Unconfigured tenant data (empty region, socials, contacts) is hidden, never shown as a placeholder.
- Keep everything added today:
  - avatar (CMT-022);
  - unread notifications badge;
  - socials hidden when empty, opened with noopener;
  - the guest CTA accessible name at every width;
  - the signed-in menu (`decision-header-auth-state`).

## Acceptance criteria

1. The header and footer HTML use the reference's class structure, landmarks and `data-element` attributes. Check with a structural diff against `ref-header.html` and `ref-footer.html`.
2. All existing header and footer contract tests pass: Sixteen Header*, Fixcity Footer* and Header*, plus `BladeTemplatesCompileTest`.
3. There are no axe serious or critical violations in header or footer at 375, 768 and 1440 px, as guest and as a logged-in user.
4. Locales it, en, de and es render. No raw translation keys, no `href="#"`, no placeholders.

## Verification

(filled in at the end)

### Footer (2026-10-26)

- `sections/footer/v1.blade.php` renders `footer.it-footer#footer` > `it-footer-main` (brand through `partials/brand-mark`, `footer-items-wrapper` columns with `footer-heading-title` + `footer-list`, contacts in `footer-info`, "Seguici su" as `list-inline social`, `footer-bottom`) > `it-footer-small-prints` (copyright). Same props and keys as before, plus `social_title` and `eu_logo`, which the JSON already has.
- Contacts and socials come only from `GetEnteDataAction` (DCP-289). Empty fields are hidden. Without contacts the "Contatti" heading is gone, and without socials the column is gone. Socials use `target="_blank" rel="noopener noreferrer"`. Links go through `FrontofficeUrl` (tel:, mailto: and external links pass unchanged), and a link without a URL is dropped.
- Legal links get the reference `data-element` values (`privacy-policy-link`, `legal-notes`, `accessibility-link`) from their path when the JSON does not declare them.
- Class coverage against `ref-footer.html`: 29/31. Missing `ue-logo` (eu_logo.src is empty by decision D198-4) and `col-md-6` (the reference's two-column services sub-grid; our JSON has no 15-item group).
- Tests, run with the scratch runner:
  - New: `tests/Feature/Components/FooterV1DesignComuniStructureTest.php`, 4 passed.
  - `NoFakeEnteDataInFrontOfficeContentTest` + `BladeTemplatesCompileTest`: 650 passed, 1 failed. The failure is a header.json region placeholder, owned by the header.
  - `FooterJsonBrandAndLinksSsotTest`, `FooterLocalizedLinksTest`, `CookieBannerPagesTest`: green.
  - `EnteDataFrontOfficeTest`: all footer assertions green. Six failures remain, all on `.cmp-contacts` help boxes, not the footer.
  - `FooterLinksResolveTest`: 4 failed. `/xx/blog` returns 500 because the scratch schema has no `articles.content_type` (environment, not markup).
- PHPStan on the new test: 0 errors.

## Header verification (2026-10-26)

- `v1.blade.php` renders `it-header-wrapper` > slim > center > navbar. 35 of 40 reference structural classes are present. The 5 missing ones belong to data not yet configured: socials, region name, secondary menu, plus the `link-list` of the language menu, which is now covered (link-list-wrapper / link-list / list-item). `tailwind.blade.php` is removed, because nothing uses it any more.
- `user-dropdown` is now a `<button>` with `aria-label` equal to the visible name, not an `<a href="#">`. It also has "Segnalazioni che seguo".
- axe (wcag2a/aa, 21a/aa), scoped to `header.it-header-wrapper` and `footer.it-footer`, on `/it`, `/en`, `/de`, `/it/tickets`, `/it/mappa-sito` and `/it/domande-frequenti`, at 375, 768 and 1440 px, as guest and logged-in user: **0 violations in 36 out of 36 runs**, and 0 `href="#"`. Before the fix, the logged-in state had `label-content-name-mismatch` (serious) and `button-name` (critical). Script: scratchpad `axe-run/chrome-axe.mjs`, with a scratch-auth-fixture session.
- Header tests: Sixteen 670 passed, Fixcity 7 passed. Footer tests: `FooterV1DesignComuniStructureTest` and Fixcity Footer*, all green.
- Fake data removed: the region names "Demo", "Name of the Region" and the others in `header.json`. The region comes from `config('ente')` (DCP-289).

## Update 2026-10-26 (D35): "Demo" in the slim bar and the reference avatar

Supersedes the line above that says the region name "Demo" was removed from `header.json`: the decision is now the opposite, see [D35](../bmad/decision-log.md) and [study](../bmad/header-design-comuni-study.md).

- `header.json` `region.name` = "Demo" in it, en, de, es. Plain text (`span.navbar-brand`), no `href`. The ente from `config('ente')` wins, and the link name follows it (`RegionPortalLinkLabels::resolve($region, $ente->region_name)`).
- The personal area button uses the reference numbers: white 24px `.rounded-icon`, green 18px `icon-primary` glyph, 16px/600 label from `lg`, hover `#006342`. CSS only, in `resources/css/app/22-header-shell.css`.
- Tests: new `tests/Feature/Components/HeaderSlimBarContractTest.php` (13); `NoFakeEnteDataInFrontOfficeContentTest` admits only `region.name = Demo`; `EnteDataFrontOfficeTest` expects "Demo" once, as plain text, when the ente gave no data.
- Pixels at 375, 768 and 1440 px, four languages, guest and signed in: see the study table.


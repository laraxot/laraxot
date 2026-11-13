---
title: "Sixteen test suite: stale contracts and real bugs"
type: story
status: review
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, sixteen, tests, pest, layout, header, cookiebar, bootstrap-italia]
qmd: "sixteen suite failing tests stale contract layouts design-comuni tests slug fromStoredUrl external social rating legend badge slot ComunePagesTest"
related:
  - ./front-office-completeness-audit.story.md
  - ../bmad/decisions/canonical-front-office-shell.md
  - ../page-directory-structure.md
issues: []
discussions: []
---

# Sixteen test suite: stale contracts and real bugs

Status: **review**

## Why

On 2026-10-26 the full Sixteen run gave `39 failed, 2 skipped, 745 passed` (open item 8 of the front office audit).
Each failing test was checked against the git history of the test and of the view: either the app is wrong (fix the app),
or the test describes a contract that was changed on purpose (update the test and cite the change). No test was deleted
and no assertion was weakened.

## App bugs fixed

| Test | Cause | Fix |
|---|---|---|
| `SegnalazioniElencoPageTest` (test page), `MainLayoutMetadataFallbackTest` | fd4a68535f deleted `layouts/design-comuni` (decision `canonical-front-office-shell`) but `pages/tests/[slug].blade.php` still fell back to it: every `/it/tests/*` page without a JSON layout returned 500 | fallback is now `layouts.app` |
| `HeaderTenantFallbackTest` (socials) | `FrontofficeUrl::fromStoredUrl` treated every URL as a site path: `https://www.facebook.com/fixcity` became `/it/fixcity`, `tel:` and `mailto:` too | URLs with another host or a non-web scheme are returned unchanged; new test `tests/Feature/Infrastructure/FrontofficeUrlExternalLinksTest.php` |
| `BootstrapItaliaComponentsTest` prop defaults | `bootstrap-italia/badge` read `$slot` when rendered without one; `bootstrap-italia/rating` printed a radio group without a legend (no accessible name) | `$slot ?? ''`; default legend `pub_theme::ui.feedback_rate_stars` |
| (found on the way) | `ticket-list/design-comuni` passed `phone` but the 4 `fixcity::ticket_list.contacts.phone` strings use `:value` (merge 2105e0528e) | placeholder passed as `value` |

## Stale tests updated

| Test | Evidence | New contract |
|---|---|---|
| `SegnalazioniElencoPageTest` (homepage group) | `/it` is now `pages/home.json` (story `homepage-design-comuni-rebuild`); the list lives at `/it/tickets` (DCP-104); `tests.ticket-list.json` was removed in e040344c0b | same assertions on `/it/tickets` (one public ticket seeded so the filter rail renders); parity page `/it/tests/segnalazioni-elenco`; contacts title is "Hai bisogno di aiuto?" since e39f3cfda2 |
| `MainLayoutMetadataFallbackTest` | `pages/administration` and `pages/news` were removed in 5d60a0430f and are forbidden (`page-directory-structure.md`) | `/it/tests/amministrazione` and `/it/blog` |
| `BootstrapItaliaComponentsTest` | "Ho capita" is a typo (Gdpr `alert_ok` = "Ho capito"); `assertSee('type="radio"')` escapes the quotes, so it never matched; `tests.bootstrap-italia-form-integration` never existed in any commit | typo fixed, `assertSee(..., false)`, form composed inline with `$this->blade()` |

## Not changed: `ComunePagesTest`

Kept as is on purpose (owner decision). The tests target `comune.*` named routes and a `Modules\Fixcity\Models\News` model that
do not exist (Folio + CMS only, no controllers). Replacement map, for a future story:

| Test(s) | Intent | Real page today |
|---|---|---|
| homepage, responsive, accessibility, bootstrap italia, font awesome | municipal homepage chrome | `/it` (`home.json`); icons are Bootstrap Italia sprites, not Font Awesome |
| homepage recent tickets | latest reports on the home | `ticket.map-preview` block on `/it`, list on `/it/tickets`; `Ticket` has `content`, not `description` |
| homepage recent news, novita (2) | news listing | `/it/blog` with `Modules\Blog\Models\Article` (`content_type = post`) |
| eventi | events listing | `/it/events` |
| servizi (2) | services catalogue | `/it/services` exists but its markup changed in b331d8dc54 and c48a4fcc1c (no `#service-search`, no auth note) |
| service category directory | link to the reports list | `/it/lista-categorie` links to `/it/tickets` with an absolute URL (`FrontofficeRoutes::ticketsIndex()`), so `href="/it/tickets"` does not match |
| contatti (3), leaflet, contact form (3) | contacts page, hours, map, form | no real page; contacts live in `sections/footer.json`; parity only: `/it/tests/assistenza-01-dati`; maps use `<map-lit>` on `/it/tickets` |
| documenti, anagrafe, tributi, urbanistica, prenotazioni | other municipal services | no real page; parity only (`/it/tests/documenti-dati`, `/it/tests/appuntamento-*`); `lista-categorie` deliberately shows reports only |

## Verification

- Single files: Segnalazioni 9/9, Header 3/3, FrontofficeUrl 5/5, MainLayout 4/4, BootstrapItalia 21/21.
- PHPStan on the touched PHP files: 0 errors. Pint: passed.
- Full run: see the final line below.

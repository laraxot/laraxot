---
title: "Front office completeness audit and fixes"
type: story
status: review
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, sixteen, front-office, audit, comments, auth, blog, tickets]
qmd: "front office audit completeness comments gravatar header badge search blog pagination receipt password reset placeholder contacts"
related:
  - ../../../../Modules/Comment/docs/stories/cmt-022.front-office-thread-editor-avatar.story.md
  - ../../../../Modules/User/docs/stories/auth-flow-redirects-reset-token.story.md
  - ../../../../Modules/Blog/docs/stories/blog-events-listing-body-and-search.story.md
  - ../../../../Modules/Fixcity/docs/stories/citizen-receipt-photos-rating-and-real-phone.story.md
issues: []
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/93"]
---

# Front office completeness audit and fixes

Status: **review**

## Why

The user asked for a full study of the front office, with no list of fixes given: "sei te che devi trovare quello che manca". Two read-only audits ran on 2026-10-26:
- a browser crawl of 41 URLs, as guest and logged-in user, at 375 and 1440 px;
- a code audit of 7 citizen journeys.

Each finding was checked on disk before it was fixed. Two findings were wrong and are noted below.

## Done (each fix has its own story and tests)

| Area | Fix | Owner story |
|---|---|---|
| Comments | Submit was broken: `ui.text` was bound instead of `uiConfig.text`. The thread view did not compile. Now: Filament `MarkdownEditor` in `CommentForm`, a Reply button, nested replies, avatar from the profile or Gravatar | Comment CMT-022 |
| Header | The citizen's avatar replaces the initial. An unread notifications badge sits on Notifiche. "Seguici su" is hidden when there are no socials | this story, CMT-022 |
| Auth | Login and registration sent users to the 404 `/dashboard`. The reset link had no token. The password was hashed twice. "Accedi" now returns to the previous page. Signed-in users are redirected away from login and register. Owners can delete their account | User auth-flow-redirects-reset-token |
| Blog and events | Real pagination (at most 9 posts were reachable before). Upcoming events come first. The full body renders (headings, images). Search is a working GET form | Blog blog-events-listing-body-and-search |
| Tickets | The receipt email is really sent. Photos and the rating prompt show on the track page. The fake `tel:050505` is gone | Fixcity citizen-receipt-photos-rating-and-real-phone |
| Content | The placeholder contacts (05 0505 and `#` links) are removed from tickets/create, confirmation, blog and events. Rating labels are translated ("1 stella") | this story |
| Repo | The `.gitignore` rule `tests/` hid every new module test. It is now `/tests/` | this story |

## Wrong findings (not changed)

- "Ticket comments only for the owner": that is deliberate. The thread between citizen and municipality is private (see the `ticket-detail` block comment).
- "UserPolicy has the wrong parameter types": `Post` was an alias. The real cause of the 403 was that the Fixcity User has no registered policy.

## Open: needs a decision or another owner

1. **The dev DB is missing 216 migrations.** Among them are `articles.content_type` and `ticket_activities.visibility`, so blog, events, ticket detail and track return 500 on dev. Run `php artisan migrate` only with the owner's OK, because the DB is shared.
2. POST `/account/delete` fails: `consents.subject_type` is missing. This is the same schema issue (Gdpr `RevokeGdprConsentsAction`).
3. Email verification is auto-set at registration. A product decision is needed (User story).
4. The header has placeholder tenant data, such as "Demo" and "Nome della Regione" in `sections/header.json`, and the footer has no contacts. The data controller has to supply the real values.
5. The `/it/tests/*` reference pages are public and use lorem ipsum and YouTube embeds. Decide whether to keep them in production.
6. Gravatar is a third-party host. The cookie policy must mention it (data controller decision; D-03 is superseded by CMT-022).
7. Comments are auto-approved (`automatically_approve_all_comments => true`). For a public administration, moderation is safer. This is a product decision.
8. The pre-existing Sixteen failures (39 in the suite on 2026-10-26) are in `ComunePagesTest` (dead routes and model), `SegnalazioniElencoPageTest` (an old homepage contract), `HeaderTenantFallbackTest` (header v1) and `BootstrapItaliaComponentsTest`. None of them touches the files changed here.
9. The search covers tickets only. A real site-wide results page needs a story.

## Second pass (2026-10-26 evening)

Done:
- **Site-wide search:** the CMS page `ricerca.index.json` plus `SearchFrontOfficeAction` cover tickets, posts, events and pages. The stub `pages/ricerca.blade.php` is removed. Story: Fixcity `site-wide-front-office-search`.
- **Unknown detail slugs now return 404.** The site map page gets its breadcrumb and missing sections, and a test pins the sitemap without template URLs. Story: Sixteen `front-office-detail-not-found-and-sitemap-page`.
- **External social, tel and mailto links** are no longer rewritten as `/it/...`. The tests pages no longer return 500 (the layout was deleted). Badge, rating legend, and the ticket-list phone placeholder are fixed. Stale tests now target the current pages. Story: Sixteen `sixteen-suite-stale-contracts`.
- **Repairs after another session's merge:**
  - comment reply avatar, and the Gravatar view;
  - the `deleteOwnAccount` ability;
  - the user menu in the canonical v1/tailwind header;
  - the conflict markers in `blog/detail.blade.php`;
  - the fake 05 0505 that came back.
- **Templates:** `layouts/navigation` and `blog/category-filter` compile again (640 out of 640).
- The root TestCase also rolls back the `blog` connection, so tests no longer leak articles.

Still open, on top of the list above:
- `ce04aee28e` deleted the 57 tenant configs again (`config/test`, `config/com`). Restoring them needs the user's OK.
- The "double counters" in the personal practices page are a wrong finding: they match the design comuni reference (page index plus section heading).
- `ComunePagesTest` (23 tests) targets routes and a model that do not exist. Deleting or rewriting it needs the user's OK; the replacement map is in the `sixteen-suite-stale-contracts` story.

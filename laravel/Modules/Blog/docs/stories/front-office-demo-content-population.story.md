---
title: "Front office demo content: posts, events, comments and tickets"
type: story
status: done
module: Blog
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, blog, seeders, demo, events, tickets]
qmd: "demo content seeder posts events comments tickets populate front office DemoArticleCatalog FixcityDemoSeeder"
related:
  - ./article-type-parental-sti-post-event.story.md
issues: []
discussions: []
---

# Front office demo content

Status: **done**

## Request

The user asked (2026-10-26): "popola il blog e gli eventi con cose adeguate, crea seeders e factories, popola tutto il sito, anche i tickets".

## State before

- The dev DB was empty: 0 users, articles, tickets and comments. 215 migrations were pending.
- `DemoContentSeeder` had several problems:
  - it created blog categories with a random UUID and no slug, in the `categories` table, which is SHARED with the ticket categories, so "Cultura" and "Eventi" showed up among the categories of the reports;
  - it used placeholder SVG images;
  - it wrote `content`, but the body is read from `content_blocks`;
  - it inserted comments with columns that do not exist (`article_id`).

## Done

1. Backup in the scratchpad (`dev-db-backup-before-migrate.sql.gz`, 110 tables, practically empty), then `php artisan migrate` (no `--force`): 215 DONE, 0 pending.
2. `FixcityDemoSeeder` (already present) created the accounts, 33 ticket categories, 547 tickets, comments and hours.
3. `DemoContentSeeder` was rewritten, with its data in `Demo/DemoArticleCatalog.php`:
   - 8 news items and 6 events with plausible civic texts in it and en;
   - real Design Comuni images (`notizia-*.png`, `eventi-*.png`);
   - the body in `content_blocks`;
   - events dated relative to today (5 upcoming, 1 past);
   - 3 comment threads per article, with replies from demo citizens.
   It is idempotent (`updateOrCreate` by slug; comments only when an article has none). It creates no categories, because the table is shared.
4. 8 junk categories left by the old seeder were removed (exact IDs, no ticket or article used them), together with 9 partial articles.

## Verification

- On the dev DB:
  - `/it/blog`, `/en/blog`, `/it/events`, `/it/blog/piano-asfaltature-autunno` and `/it/events/festa-delle-associazioni` return 200 with the contents;
  - the detail pages show comments with replies and avatars.
- Running the seeder again leaves 14 articles and 42 comments, so it creates no duplicates.
- PHPStan reports 0 on the seeder and the catalog.

## Correction (2026-10-26, evening)

The figures above (14 articles, 547 tickets, 42 comments) were the seeder's own at that moment, not what the DB held later. The lists were empty because the older articles had `articles.type` NULL: see [front-office-demo-content-type-backfill](./front-office-demo-content-type-backfill.story.md).

Counts read from the DB at the end: 18 posts, 11 events, 538 tickets, 85 users (74 with `@fixcity.demo`), no article with `type` NULL.
Verified on the dev site: blog, events and tickets answer 200 in `/it` and `/en`, with no raw lang keys and no legal-incomplete block; `/it/tickets/554`, `/298`, `/287`, `/it/blog/piano-asfaltature-autunno` and `/it/events/festa-delle-associazioni` answer 200.

## Open

- ~~The homepage still shows no news or events (audit item 18).~~ Done: see [home-news-events-sections](../../../../Themes/Sixteen/docs/stories/home-news-events-sections.story.md).
- Two short articles with no comments and no English text are old seeder residue: `sport-nel-verde-le-iniziative-della-prossima-edizione` and `torneo-di-beneficienza-tutti-in-pista` (near-duplicates of `sport-nel-verde-parchi`). Delete or complete them: an editor's decision, not taken here.
- Some event cards in `/en/events` still show Italian titles (legacy events without an `en` text).
- The rating widget text on `/it/blog` wraps oddly (a few words per line): not investigated.
- `2026_10_26_add_content_type_to_articles.php` was re-created by another session and applied by `migrate`. The column is unused: the code reads `type`. The conflict with `bmad/content-type-contract.md` is described in the backfill story.

## Seeder contract and test isolation (2026-10-27)

`DemoContentSeederTest` and `BlogFrontOfficeNovitaTest` asked for three things the seeder did not do. It does them now:

- it writes nothing outside `local`, `testing` and `demo`, like `FixcityDemoSeeder`;
- each article ends with an `image` block (the main image) whose `credit` reads "Foto: Asset Design Comuni (Developers Italia)". `ResolveArticleBodyAction` makes it the lead figure, so the detail page shows the credit under the image and the image only once;
- comments have an explicit state: the first two threads and their replies are approved, the third stays pending and is not shown. When the DB has no `@fixcity.demo` citizens, the seeder runs `DemoUsersSeeder` first.

Two HTTP assertions were wrong, not the page: Blade escapes the apostrophe (`&#039;`), and the SEO layout adds ` | FixCity` to `<title>`.

Seven other Blog failures were not Blog bugs. The root `tests/TestCase.php` ran `migrate` inside `setUp()`, after `DatabaseTransactions` had opened the transactions. In MySQL a DDL statement commits implicitly, so the rows of the first test in each process stayed in the scratch DB and later counts were off by 8 or 14. Migrations now run in `beginDatabaseTransaction()`, before the transaction opens.

Proof: Blog suite 42 passed (was 13 failed, 29 passed). On the dev site the credit shows on `/it/blog/piano-asfaltature-autunno` and `/en/events/festa-delle-associazioni`, the image appears once, and `/it`, `/it/blog`, `/it/events`, `/en/blog` answer 200.

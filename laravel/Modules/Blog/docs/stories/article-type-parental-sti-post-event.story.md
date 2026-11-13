---
title: "Articles: post and event are Parental STI types, not content_type"
type: story
status: done
module: Blog
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, blog, parental, sti, events, posts]
qmd: "article type parental sti post event content_type removed childTypes Post Event"
related:
  - ./blog-events-listing-body-and-search.story.md
issues: []
discussions: []
---

# Articles: post and event are Parental STI types

Status: **done**

## Problem

`/it/blog` and `/it/events` returned a 500 on the dev DB: `Unknown column 'content_type'`. Two migrations (`2026_10_15_000001`, and today's `2026_10_26_add_content_type_to_articles`) added a second discriminator next to the existing `articles.type`. `Article` already used Parental `HasChildren`, but it had no child classes, so `type = post` would have been read as a class name. That is why someone worked around it with a new column.

The user's direction (2026-10-26): "e' il post che puo essere type article / type event".

## Decision

- `Article::$childTypes = ['post' => Post::class, 'event' => Event::class]`. The new `Modules\Blog\Models\Post` and `Modules\Blog\Models\Event` use `HasParent`. They keep the `article` morph class, so comments, ratings and tags are unchanged.
- Every query, form, table, factory, seeder and test uses `type`. The two `content_type` migrations are removed: they never ran on dev, and the column does not exist there.
- Nothing changes in the Event module, which has its own unrelated `content_type`.

## Verification

- Live on the dev DB, with no migration (`php artisan serve`, GET only):
  - `/it/blog`, `/it/events`, `/en/events`, `/it` and `/it/ricerca?q=buca` return 200;
  - `/it/blog/nonexistent` returns 404.
- Hydration: a row with `type=event` loads as `Modules\Blog\Models\Event`, and the morph class stays `article`.
- Tests:
  - Seo `SitemapDetailTemplatesTest` passes (2).
  - Sixteen search, detail 404 and article body tests pass (24, including the search modal test updated to `/ricerca`).
  - Blog: 27 pass. The 15 failures are older and are not caused by this change: 14 come from the demo seeder (`Duplicate entry '' for key 'categories.PRIMARY'`) and 1 is a SAVEPOINT error.
- PHPStan reports 0 on Blog app, factories, the Fixcity search and the touched tests.

---
title: "Docs index audit — Theme Zero"
type: story
status: done
created: 2026-09-03
---

# Docs index audit — Theme Zero

**BMAD phase:** Build (docs-only maintenance, no application code).

Audit of `Themes/Zero/docs/` (213 `.md` files). Rewrote `docs/index.md` as the single
topic-organized entry point covering all 213 files, without deleting, renaming or moving
any existing file. Detected duplicate/superseded clusters (exact md5 duplicates, deprecated
snake_case stubs, uppercase/lowercase twins, redundant index files, historic conflict-resolution
family) and grouped them under "Storico / da consolidare" in the new index, each still linked
at its original path per `docs-archive-policy.md`. Verified via link-vs-filesystem diff that
all 213 files are reachable from `index.md`.

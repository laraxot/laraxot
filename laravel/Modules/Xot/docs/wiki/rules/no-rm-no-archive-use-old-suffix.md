---
title: "No rm, no archive folders, use .old suffix"
type: rule
module: Xot
confidence: high
created: 2026-07-12
updated: 2026-07-12
tags: [xot, governance, files, migrations, old-suffix]
related:
  - ../../../../../docs/wiki/rules/no-rm-no-archive-use-old-suffix.md
---

# No rm, no archive folders, use .old suffix

Xot follows the root governance rule: do not remove historically meaningful files with `rm`, and do not create `archive`, `_bak`, or `migrations_archive` folders for new cleanup work.

Disable obsolete files in place with `.old`, then document the decision in the owning module/theme docs.

For migrations, the only active files are `database/migrations/*.php`; deprecated migrations become `*.php.old` in the same directory.

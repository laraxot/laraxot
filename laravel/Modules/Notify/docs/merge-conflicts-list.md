---
title: "Merge conflict markers — Notify"
type: concept
tags: [merge, conflicts, list]
created: 2026-07-14
updated: 2026-07-14
qmd: "merge-conflicts-list merge conflict markers — notify"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Merge conflict markers — Notify

## Stato (2026-05-26)

- [x] `resources/svg/*.svg` (13) — HEAD (SVG reale, non puntatore Git LFS)
- [x] `app/Filament/Resources/**/Tables/*Table.php` (3 conflitti + 3 allineati) — corpo HEAD, firma `public function getTableColumns()` (non `static`: vedi memoria sotto)
- [x] `docs/redundancy-report.md` — HEAD
- [x] Rimossi `*.php.up` backup con marker

## Verifica

```bash
cd laravel && git grep -n '^<<<<<<< ' -- Modules/Notify/
./vendor/bin/phpstan analyse Modules/Notify --memory-limit=2G
```

## Memoria

[`docs/wiki/memories/merge-collision-notify-lessons.md`](wiki/memories/merge-collision-notify-lessons.md)

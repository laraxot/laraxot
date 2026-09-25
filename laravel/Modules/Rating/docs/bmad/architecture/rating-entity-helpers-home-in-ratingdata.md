---
title: "Helper rating (label/path/fieldName) — sede RatingData"
type: architecture
module: Rating
status: accepted
created: 2026-09-23
updated: 2026-09-23
qmd: "formFieldLabel ratingFieldName ratingValuePath RatingData HasRatingsTrait SSoT"
related:
  - ./ratingdata-getxlsfields-caller-resolve.md
  - ./form-field-label-and-xls-path-home.md
  - ../stories/5.231-stale-callers-after-ratingdata-move.story.md
---

# Sede helper (post-swarm 2026-09-23)

| Helper | Sede SSoT | Note |
|--------|-----------|------|
| `formFieldLabel` | `RatingData` | presentazione entità |
| `ratingFieldName` | `RatingData` | schema form `ratings.{id}.pivot.*` |
| `ratingValuePath` / `ratingXlsValuePath` | `RatingData` | export `ratings_by_id.*` |
| `getXlsFields` / `criteriaToXlsFields` | `RatingData` | catalogo export |
| relazioni / sync / form schema build | `HasRatingsTrait` | host |

**Trappola:** due path diversi (`ratings.*` form vs `ratings_by_id.*` export) sono
volontari — non unificarli senza story. Dopo ogni move: sweep caller (5.231).

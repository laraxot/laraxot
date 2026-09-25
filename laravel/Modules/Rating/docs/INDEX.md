# Rating Module — Documentation Index

**Last updated:** 2026-07-28  
**Stats:** 151 files | 18 categories | 16+ indexed resources

This module's docs are organized under `laravel/Modules/Rating/docs/`.

---

## ⚠️ Architectural Rules

- **[No Http Controllers — Folio + Actions + Filament](../../../../docs/wiki/rules/no-controllers-rule.md)** — Absolute rule: Rating module must NOT use Http\Controllers. Rating HTTP endpoints use Folio + Actions. Admin uses Filament.

## Core Design & Architecture

- **Reorderable Table Pattern** (HasXotTable): see IndennitaResponsabilita module docs for the core design; Rating adoption story: `docs/stories/5.96-rating-resource-reordering-adoption.story.md`

## Module-specific Docs

- `docs/stories/5.96-rating-resource-reordering-adoption.story.md` — QA + verification for reordering in RatingResource

## Second Brain References

- Project wiki rules: `docs/wiki/rules/`
- BMAD stories: `docs/bmad/stories/`
- Architecture decisions: `docs/architecture-decisions/` (root)
- Sprint status: `docs/sprint-status.yaml`

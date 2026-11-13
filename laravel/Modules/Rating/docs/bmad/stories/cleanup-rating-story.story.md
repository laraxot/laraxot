---
title: "cleanup rating 2026 09 22.story"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "cleanup rating 2026 09 22.story"
issues: []
discussions: []
status: superseded
superseded_by: cleanup-rating.story.md
---

# Story: Cleanup Rating Module

## BMAD Method Applied
- **Scale**: Feature/module (Rating)
- **Impact**: Rating system, RatingResource, RatingPolicy, RatingFilter
- **Quality Gates**: PHPStan, Pint, Rating-specific quality checks

## Tasks
1. **Understand**: Review Rating module structure, RatingResource, RatingPolicy
2. **Plan**: Identify duplicate Rating files, optimize Rating table, fix PHPStan issues
3. **Implement**: Clean up Rating artifacts, fix table column names, remove dead code
4. **Verify**: Run `phpstan analyse Modules/Rating`, `pint`, `phpmd`
5. **Document**: Update sprint-status.yaml, add to docs/bmad-stories

## References
- Rating/docs/architecture.md
- Rating/docs/INDEX.md
- Rating/Comments and reviews handling

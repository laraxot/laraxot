# Story: Cleanup Rating Module

## BMAD Method Applied
- **Scale**: Feature/module (Rating)
- **Impact**: Rating system, RatingResource, RatingPolicy, RatingFilter
- **Quality Gates**: PHPStan, Pint, Rating-specific quality checks

## Tasks
1. **Understand** — Review Rating module structure, RatingResource, RatingPolicy
2. **Plan** — Identify duplicate Rating files, optimize Rating table, fix PHPStan issues
3. **Implement** — Clean up Rating artifacts, fix table column names, remove dead code
4. **Verify** — Run `phpstan analyse Modules/Rating`, `pint`, `phpmd`
5. **Document** — Update sprint-status.yaml, add to docs/bmad-stories

## References
- Rating/docs/architecture.md
- Rating/docs/INDEX.md
- Rating/Comments and reviews handling

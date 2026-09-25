# Story: Cleanup Xot Module (High Priority)

## BMAD Method Applied
- **Scale**: Feature/module (Xot base module)
- **Impact**: Architecture, XotBaseModel, XotBaseMorphPivot, XotBasePivot, XotBaseRelationManager, XotBaseTable
- **Quality Gates**: PHPStan level 10, Pint style, XotBaseTable naming convention

## Tasks
1. **Understand** — Review Xot architecture, identify breaking changes, map affected modules (Progressioni, Ptv, User, Rating, Notify)
2. **Plan** — Define minimal fix strategy: fix table column names, recover broken methods, ensure XotBaseTable compliance
3. **Implement** — Apply fixes to Xot modules, update XotBaseTable, XotBaseRelationManager, XotBasePivot
4. **Verify** — Run `phpstan analyse Modules/Xot`, `pint`, `phpmd`
5. **Document** — Add story entry to sprint-status.yaml, update Xot docs

## References
- Xot docs/bmad-method.md
- Xot docs/architecture.md
- Xot BaseTable conventions (XotBaseTable.php)

# Subagent-A Task: Resolve High-Complexity Module Conflicts

## Random Order: Xot → IndennitaResponsabilita → Media

## Current State (BMAD Understand Phase)
- **Xot**: 126 AA conflicts (XotBaseResourceTable, XotBaseRelationManager, XotBaseTable, HasXotTable)
- **IndennitaResponsabilita**: 90 AA conflicts (Policies, lang en/it, translation actions)
- **Media**: 89 DU conflicts (Media module, assets, pipeline)

## Parallel Execution Plan

### SUBAGENT-A.1: Xot (BASE MODULE - CRITICAL)
1. **Understand**: Xot is the core XotBase module - check XotBaseModel, XotBaseTable, XotBaseResourceTable
2. **Plan**: Check XotBaseTable column naming, XotBaseResourceTable compatibility, HasXotTable trait
3. **Implement**: 
   - Fix XotBaseResourceTable (UU conflicts)
   - Fix XotBaseRelationManager (UU conflicts)  
   - Fix XotBaseTable (UU conflicts)
   - Fix HasXotFactory (M - modified)
   - Fix XotBaseModel.php (M - modified)
4. **Verify**: `php -d memory_limit=2G phpstan analyse Modules/Xot`
5. **Document**: Update Xot/docs/bmad/stories/cleanup-xot-2026-09-22.story.md

### SUBAGENT-A.2: IndennitaResponsabilita
1. **Understand**: Check policies, lang, translation actions
2. **Plan**: Resolve AA conflicts, fix lang inconsistencies
3. **Implement**: Stage all AA files, verify consistency
4. **Verify**: PHPStan + Pint on lang/ and policies/
5. **Document**: Update story

### SUBAGENT-A.3: Media
1. **Understand**: Media module structure, DU conflicts (deleted by us)
2. **Plan**: Resolve DU conflicts (deleted upstream files)
3. **Implement**: Accept upstream deletions, keep local additions
4. **Verify**: PHPStan + Pint
5. **Document**: Update story

## Quality Checks (All Subagents)
```bash
# For each module
php -d memory_limit=2G vendor/bin/phpstan analyse Modules/Xot
vendor/bin/pint
vendor/bin/phpmd Modules/Xot text phpmd.xml
```

## Second Brain Update
- Update: `docs/sprint-status.yaml` story 5.124 progress
- Track: All module cleanup progress
- Reference: BMAD story files in each module/docs/bmad/stories/

## References
- BMAD Method: `Xot/docs/bmad-method.md`
- Quality Guide: `Xot/docs/phpstan-code-quality-guide.md`
- XotBaseTable: `Xot/docs/wiki/rules/xot-table-method-names.md`

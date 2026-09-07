ISSUE TEMPLATES (crea manually o via gh cli):
# Issue 1 — HasXotForm trait + XotBaseResourceForm instance refactor
Repo: laraxot/module_xot_fila5
Title: [structural] XotBaseResourceForm must use HasXotForm trait with instance configure()
Body: Current XotBaseResourceForm uses static getFormSchema(). Must follow Table pattern: HasXotForm + $instance = app(static::class) + configure(). Link: docs/bmad-stories/STORY-2026-08-STRUCTURAL-XOT-FORM-INFO.md
Labels: architecture, refactor, xot

# Issue 2 — HasXotInfolist trait + XotBaseResourceInfolist instance refactor
Repo: laraxot/module_xot_fila5
Title: [structural] XotBaseResourceInfolist must use HasXotInfolist with instance configure()
Body: Same pattern as Form. Remove static getInfolistSchema() from base; move to trait.
Labels: architecture, refactor, xot

# Issue 3 — Remove TransTrait duplication
Repo: laraxot/module_xot_fila5
Title: [clean] TransTrait duplicated in XotBaseResourceTable (already in HasXotTable)
Body: Remove use TransTrait from XotBaseResourceTable.
Labels: cleanup, xot

# Issue 4 — Update docs for Tables + Schemas architecture
Repo: laraxot/module_xot_fila5
Title: [docs] Document Filament Tables / Schemas architecture for all modules
Body: Ref docs/wiki/filament-tables-schemas-architecture.md + module docs.
Labels: docs, xot

# Issue 5 — HasXotForm / XotBaseResourceForm static → instance pattern
Repo: laraxot/module_xot_fila5
Title: [refactor] HasXotForm: align XotBaseResourceForm static to instance $this->getFormSchemaColumns()
Body: BMAD story: docs/bmad-stories/STORY-2026-08-HASXOTFORM-INSTANCE.md. Pattern: $instance = app(static::class) + $instance->getFormSchemaColumns(). Remove `static::` from getFormSchema/getFormSchemaColumns/getSteps/optionLabelFromRecord/getStepByName.
Labels: refactor, xot, hasxotform

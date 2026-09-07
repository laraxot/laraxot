=== SESSION MEMORY — Xot/Module Refactoring ===
LAST UPDATE: $(date)
USER: marco (pi session)

COMPLETED (do not repeat from scratch):
- XotBaseResourceTable.php: removed TransTrait (redundant with HasXotTable)
- XotBaseResourceForm.php: pattern static; already uses getFormSchemaColumns() (not hardcoded 2)
- HasXotForm.php: form() already uses $this->getFormSchemaColumns() (correct)
- User/OauthPersonalAccessClientResource.php: removed getTableColumns/getTableFilters/Actions/BulkActions (duplicate of cluster Tables/)
- Media/MediaTable.php: fixed duplicate import (alias MediaResourceClass)
- Employee/AbsenceRequestResource.php: removed invalid #[\Override] on getHeaderWidgets
- BMAD Story xot-form-refactor-01 created + Issue #99 + comment
- Issue #101 created+closed (duplicate of #99, solved)

NOT FINISHED / MUST RESTART NEXT SESSION:
- PHPStan Modules: 82 files with errors (running, not zero yet)
- Batch fix of all 82 error files in all modules (requires modular approach)
- Update docs/filament-base-classes.md with the separated Table/Schema/Forms pattern
- Apply second-brain rules: always check for redundant TransTrait; always verify #[\Override] exists in parent; always check Tables/ folder exists before adding inline table methods

DISCOVERED / SHOULD DO:
- Create docs/patterns/table-form-infolist.md documenting the instance-based (Table) vs static (Form/Infolist) design choice
- Audit all modules for duplicate TransTrait usage in Table classes
- Audit all modules for invalid #[\Override] attributes
- Use github remote laraxot for issue tracking (already configured)

TOOLS INSTALLED / AVAILABLE:
- gh CLI (github.com logged in as marco76tv)
- php -l syntax check available
- bash for batch processing

RULE: On restart, read SESSION_MEMORY.md first, then /tmp/bmad_all/STORY_INDEX.md, then Issues #99/#101.

=== SESSION UPDATE (after structural change) ===
- XotBaseResourceTable.php: TransTrait rimosso (verificato, già corretto)
- XotBaseResourceForm.php: ora abstract, usa HasXotForm, configure() instance-based ($instance)
- XotBaseResourceInfolist.php: ora abstract, configure() instance-based
- Issue #99 aggiornata con commento 5572115312
- Second brain: pattern unificato Table (HasXotTable/instance) + Form (HasXotForm/instance) + Infolist (instance)
- Prossimo step: fix PHPStan 82 errori rimanenti (modulo per modulo)
Xot refactoring introduces breaking change (static -> instance for Form/Infolist). Consuming modules (CacheLockForm, CacheForm, etc.) must update getFormSchema() from static to instance. Documented in SESSION_MEMORY.md — requires batch update across modules.
=== CONTINUA BATCH FIX (moduli in ordine alfabetico) ===
AiAssistant: controllato (0 errori visibili)
Prossimo: Billing, Catalog, Geo, User, ecc.

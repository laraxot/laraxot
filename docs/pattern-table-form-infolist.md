=== PATTERN RULE (Second Brain) ===
Rule: Table/Form/Infolist base classes
Table: abstract class extends nothing (or base), uses HasXotTable, instance-based configure() with $instance = app(static::class), abstract getTableColumns() non-static.
Form: abstract class XotBaseResourceForm, uses HasXotForm, configure() instance-based, abstract getFormSchema() non-static.
Infolist: abstract class XotBaseResourceInfolist, instance-based configure(), abstract getInfolistSchema() non-static.
Redundancy: NEVER add use TransTrait to Table (already inside HasXotTable or redundant).
Memory: /var/www/_bases/base_workorder_fila5/SESSION_MEMORY.md
GitHub Issue: laraxot/module_xot_fila5 #99

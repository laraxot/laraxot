# Issue: Structural refactor XotBaseResourceForm / XotBaseResourceInfolist → HasXotForm / HasXotInfolist

Repo: laraxot/module_xot_fila5
Branch: dev
Status: open

## Change
- `XotBaseResourceForm` → `abstract` + `use HasXotForm`; `configure()` with `$instance = app(static::class)` and `->form()`.
- `XotBaseResourceInfolist` → `use HasXotInfolist`; same pattern.
- `getFormSchema()` / `getInfolistSchema()` now instance-based (not static) per trait.
- `getFormColumns()` = 2, `getInfolistColumns()` = 2 (verified from HasXotForm.php / HasXotInfolist.php).

## Verification
- `php -l` passes on both files.
- `bashscripts/lock/` used, `docs/sprint-status.yaml` updated, second brain updated.
- No `#[\Override]` on `getInfolistSchema()` when parent `XotBaseViewRecord` doesn't declare it (batch fixed in User/Clusters).

# BMAD Story 07 — HR: compatibilità getTableFilters()

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Modulo:** `HR`
**File:** `Modules/HR/app/Filament/Resources/AbsenceRequestResource/Tables/AbsenceRequestsTable.php`
**Errore:** `method.childReturnType` — `getTableFilters()` deve restituire `array<int|string, BaseFilter>` (come `XotBaseResourceTable`), non `array<string, Action>`.

## Azione
Correggere il type hint del return della filtrazione.

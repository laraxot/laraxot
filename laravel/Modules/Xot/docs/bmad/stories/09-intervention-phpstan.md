# BMAD Story 09 — Intervention: 21 errori PHPStan

**Modulo:** `Intervention`
**Stato:** TODO
**Coordinamento:** `docs/swarm/INDEX.md` (agent-d)

## File da analizzare
- `app/Filament/Resources/InterventionResource/Schemas/InterventionForm.php`
- `app/Filament/Resources/InterventionResource/Tables/InterventionsTable.php`
- `app/Filament/Resources/InterventionResource.php`
- `app/Filament/Resources/DailyNoteResource/Schemas/DailyNoteForm.php`

## Quality gate
```bash
cd laravel/Modules/Intervention
../../vendor/bin/phpstan analyse . --no-progress --memory-limit=-1
```

# BMAD Story 05 — ViewRecord non ridefinisce getInfolistSchema

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**File:** `app/Filament/Resources/Pages/XotBaseViewRecord.php`
**Trigger:** `#[\Override]` su `getInfolistSchema()` rompe il parent inesistente.

## Regola
`XotBaseViewRecord` non espone `getInfolistSchema()`. Le pagine concrete non devono ridefinirlo; l'infolist è gestito da `XotBaseResourceInfolist` con schema separato (`Schemas/ResourceInfolist.php`).

## Acceptance criteria
- Nessun `getInfolistSchema` in `Modules/*/app/Filament/Resources/Pages/*.php`
- PHPStan 0 errori su `Modules/Xot` e `Modules/Geo`
